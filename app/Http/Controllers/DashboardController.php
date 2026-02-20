<?php

namespace App\Http\Controllers;

use App\Models\Asesor;
use App\Models\DocumentSigned;
use App\Models\Inability;
use App\Models\Insurer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isAdmin = $user->hasRole('Administrador');
        $isSalesManager = $user->hasRole('Jefe de ventas');
        $isSales = $user->hasRole('Ventas');

        $scopeQuery = $this->buildInabilityScopeQuery($user);

        $totalAfiliaciones = (clone $scopeQuery)->count();
        $afiliacionesMesActual = (clone $scopeQuery)
            ->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->count();

        $totalAseguradoras = $isAdmin
            ? Insurer::count()
            : (clone $scopeQuery)->whereNotNull('aseguradora')->distinct('aseguradora')->count('aseguradora');

        $totalUsuarios = $isAdmin
            ? User::where('status', 1)->count()
            : ($isSalesManager ? $this->getTeamUserIds($user)->count() : 1);

        $scopeIds = (clone $scopeQuery)->pluck('id');
        $documentosFirmados = $scopeIds->isEmpty()
            ? 0
            : DocumentSigned::whereIn('inability_id', $scopeIds)->count();

        $months = collect(range(5, 1))->map(fn ($offset) => Carbon::now()->startOfMonth()->subMonths($offset));
        $months = $months->push(Carbon::now()->startOfMonth());

        $monthlyRaw = (clone $scopeQuery)
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month_key, COUNT(*) as total")
            ->where('created_at', '>=', Carbon::now()->startOfMonth()->subMonths(5))
            ->groupBy('month_key')
            ->orderBy('month_key')
            ->pluck('total', 'month_key');

        $monthlyLabels = $months->map(fn ($date) => ucfirst($date->translatedFormat('M y')))->values();
        $monthlyData = $months->map(fn ($date) => (int) ($monthlyRaw[$date->format('Y-m')] ?? 0))->values();

        $paymentRaw = (clone $scopeQuery)
            ->select('forma_pago', DB::raw('COUNT(*) as total'))
            ->whereNotNull('forma_pago')
            ->groupBy('forma_pago')
            ->pluck('total', 'forma_pago');

        $paymentLabelMap = [
            'debito_automatico' => 'Débito automático',
            'mensual_libranza' => 'Mensual libranza',
        ];

        $paymentLabels = $paymentRaw->keys()->map(function ($key) use ($paymentLabelMap) {
            return $paymentLabelMap[$key] ?? ucfirst(str_replace('_', ' ', (string) $key));
        })->values();
        $paymentData = $paymentRaw->values()->map(fn ($value) => (int) $value)->values();

        $roleChartTitle = 'Top asesores';
        $roleChartLabels = collect();
        $roleChartData = collect();

        if ($isSales) {
            $roleChartTitle = 'Mis afiliaciones por aseguradora';
            $insurerRaw = (clone $scopeQuery)
                ->selectRaw("COALESCE(NULLIF(aseguradora, ''), 'Sin aseguradora') as label, COUNT(*) as total")
                ->groupBy('label')
                ->orderByDesc('total')
                ->limit(6)
                ->get();

            $roleChartLabels = $insurerRaw->pluck('label')->values();
            $roleChartData = $insurerRaw->pluck('total')->map(fn ($value) => (int) $value)->values();
        } else {
            $roleChartTitle = $isAdmin ? 'Top vendedores' : 'Top vendedores del equipo';
            $teamIds = $isAdmin
                ? User::role('Ventas')->where('status', 1)->pluck('id')
                : $this->getTeamUserIds($user);

            $sellerRaw = Inability::query()
                ->selectRaw("inabilities.user_id as seller_id, COUNT(*) as total")
                ->whereIn('inabilities.user_id', $teamIds)
                ->groupBy('inabilities.user_id')
                ->orderByDesc('total')
                ->limit(6)
                ->get();

            $sellerNames = User::whereIn('id', $sellerRaw->pluck('seller_id'))
                ->pluck('name', 'id');

            $roleChartLabels = $sellerRaw->map(function ($row) use ($sellerNames) {
                return $sellerNames[$row->seller_id] ?? 'Sin nombre';
            })->values();
            $roleChartData = $sellerRaw->pluck('total')->map(fn ($value) => (int) $value)->values();
        }

        $advisorRaw = (clone $scopeQuery)
            ->selectRaw("COALESCE(NULLIF(nombre_asesor, ''), 'Sin asesor') as advisor, COUNT(*) as total")
            ->groupBy('advisor')
            ->orderByDesc('total')
            ->limit(6)
            ->get();

        $advisorLabels = $advisorRaw->pluck('advisor')->values();
        $advisorData = $advisorRaw->pluck('total')->map(fn ($value) => (int) $value)->values();

        return view('dashboard', compact(
            'totalAseguradoras',
            'totalAfiliaciones',
            'totalUsuarios',
            'afiliacionesMesActual',
            'documentosFirmados',
            'monthlyLabels',
            'monthlyData',
            'paymentLabels',
            'paymentData',
            'roleChartTitle',
            'roleChartLabels',
            'roleChartData',
            'advisorLabels',
            'advisorData',
            'isAdmin',
            'isSalesManager',
            'isSales'
        ));
    }

    private function buildInabilityScopeQuery($user)
    {
        $query = Inability::query();

        if ($user->hasRole('Administrador')) {
            return $query;
        }

        if ($user->hasRole('Jefe de ventas')) {
            $teamIds = $this->getTeamUserIds($user);
            return $query->whereIn('inabilities.user_id', $teamIds);
        }

        if ($user->hasRole('Ventas')) {
            $salesFilterData = $this->buildSalesFilterData($user);
            $normalizedNameKey = $salesFilterData['nameKey'];
            $normalizedCodes = $salesFilterData['codes'];
            $codigoExpression = $this->normalizedColumnExpression('inabilities.codigo_asesor');
            $nombreExpression = $this->normalizedColumnExpression('inabilities.nombre_asesor');

            $query->where(function ($builder) use ($user, $normalizedNameKey, $normalizedCodes, $codigoExpression, $nombreExpression) {
                $builder->where('inabilities.user_id', $user->id);

                if ($normalizedCodes->isNotEmpty()) {
                    $builder->orWhere(function ($codeBuilder) use ($normalizedCodes, $codigoExpression) {
                        foreach ($normalizedCodes as $index => $code) {
                            $method = $index === 0 ? 'whereRaw' : 'orWhereRaw';
                            $codeBuilder->{$method}($codigoExpression . ' = ?', [$code]);
                        }
                    });
                }

                if ($normalizedNameKey !== '') {
                    $builder->orWhereRaw($nombreExpression . ' = ?', [$normalizedNameKey]);
                }
            });
        } else {
            $query->whereRaw('1 = 0');
        }

        return $query;
    }

    private function getTeamUserIds($user)
    {
        $ids = collect(json_decode($user->vendedores_id, true) ?? [])
            ->filter(fn ($id) => is_numeric($id))
            ->map(fn ($id) => (int) $id)
            ->push((int) $user->id)
            ->unique()
            ->values();

        return $ids->isNotEmpty() ? $ids : collect([(int) $user->id]);
    }

    private function buildSalesFilterData($user): array
    {
        $normalizedNameKey = $this->normalizeComparableString($user->name ?? '');

        $codes = collect();

        if (!empty($user->codigo)) {
            $codes->push($this->normalizeComparableString($user->codigo));
        }

        $matchingCodes = Asesor::all()
            ->filter(fn ($asesor) => $this->normalizeComparableString($asesor->name ?? '') === $normalizedNameKey)
            ->pluck('asesor_code')
            ->filter()
            ->map(fn ($code) => $this->normalizeComparableString($code))
            ->filter();

        $codes = $codes
            ->merge($matchingCodes)
            ->unique()
            ->values();

        return [
            'nameKey' => $normalizedNameKey,
            'codes' => $codes,
        ];
    }

    private function normalizeComparableString(?string $value): string
    {
        if ($value === null) {
            return '';
        }

        $normalizedWhitespace = preg_replace('/\s+/u', ' ', trim((string) $value));
        $ascii = Str::ascii($normalizedWhitespace ?? '');

        return strtolower(str_replace(' ', '', $ascii));
    }

    private function normalizedColumnExpression(string $column): string
    {
        $expression = "TRIM($column)";
        $expression = "REPLACE($expression, ' ', '')";

        $replacements = [
            'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U',
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
            'À' => 'A', 'È' => 'E', 'Ì' => 'I', 'Ò' => 'O', 'Ù' => 'U',
            'à' => 'a', 'è' => 'e', 'ì' => 'i', 'ò' => 'o', 'ù' => 'u',
            'Ñ' => 'N', 'ñ' => 'n',
            'Ü' => 'U', 'ü' => 'u',
        ];

        foreach ($replacements as $search => $replace) {
            $expression = "REPLACE($expression, '$search', '$replace')";
        }

        return "LOWER($expression)";
    }
}
