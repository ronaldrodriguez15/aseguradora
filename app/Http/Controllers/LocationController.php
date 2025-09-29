<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserLocation;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LocationController extends Controller
{
    // Muestra la vista del módulo
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('Administrador')) {

            $usersReport = User::where('status', 1)->get();
        } elseif ($user->hasRole('Ventas')) {

            $usersReport = User::where('id', $user->id)
                ->where('status', 1)
                ->get();
        } else {
            $usersReport = collect();
        }

        return view('geolocation.index', compact('usersReport'));
    }


    public function update(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $user = Auth::user();

        if ($user) {
            $user->latitude = $request->latitude;
            $user->longitude = $request->longitude;
            $user->save();
        }

        return response()->json(['success' => true]);
    }

    public function getLocations()
    {
        $user = Auth::user();

        if ($user->hasRole('Administrador')) {
            // Admin ve a todos con ubicación válida
            $users = User::select('id', 'name', 'latitude', 'longitude', 'document')
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->get();
        } elseif ($user->role === 'Ventas') {
            // Ventas solo se ve a sí mismo
            $users = User::select('id', 'name', 'latitude', 'longitude', 'document')
                ->where('id', $user->id)
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->get();
        } elseif ($user->hasRole('Jefe de ventas')) {
            // Decodificar vendedores asignados
            $ids = json_decode($user->vendedores_id, true);

            if (!empty($ids) && is_array($ids)) {
                $ids[] = $user->id; // también se incluye él mismo
                $users = User::select('id', 'name', 'latitude', 'longitude', 'document')
                    ->whereIn('id', $ids)
                    ->whereNotNull('latitude')
                    ->whereNotNull('longitude')
                    ->get();
            } else {
                $users = User::select('id', 'name', 'latitude', 'longitude', 'document')
                    ->where('id', $user->id)
                    ->whereNotNull('latitude')
                    ->whereNotNull('longitude')
                    ->get();

                return response()->json([
                    'users' => $users,
                    'message' => 'No tienes vendedores asignados, habla con el administrador.'
                ]);
            }
        } else {
            // Rol desconocido → solo él mismo
            $users = User::select('id', 'name', 'latitude', 'longitude', 'document')
                ->where('id', $user->id)
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->get();
        }

        return response()->json(['users' => $users]);
    }

    public function storeHourLocation(Request $request)
    {
        $userLocation = new UserLocation();
        $userLocation->user_id = $user->id;
        $userLocation->latitude = $request->latitude;
        $userLocation->longitude = $request->longitude;
        $userLocation->hora = Carbon::now()->format('H:i:s');
        $userLocation->fecha = now()->toDateString();
        $userLocation->save();

        return response()->json(['status' => 'ok']);
    }

    public function generateReport(Request $request)
    {
        $fechaDesde = $request->fecha_desde;
        $fechaHasta = $request->fecha_hasta;
        $userId     = $request->user_id; // Ojo: cambiaste el name a user_id en la vista

        if ($userId && $userId !== 'all') {
            // Reporte de un usuario específico
            $user = User::findOrFail($userId);

            $locations = UserLocation::where('user_id', $user->id)
                ->when($fechaDesde && $fechaHasta, function ($query) use ($fechaDesde, $fechaHasta) {
                    $query->whereBetween('fecha', [$fechaDesde, $fechaHasta]);
                })
                ->orderBy('fecha')
                ->orderBy('hora')
                ->get();

            $pdf = Pdf::loadView('reports.user_geolocation', compact('user', 'locations'));
            return $pdf->stream("Reporte_{$user->name}.pdf");
        } else {
            // Reporte de todos los usuarios
            $users = User::all();

            $locations = UserLocation::when($fechaDesde && $fechaHasta, function ($query) use ($fechaDesde, $fechaHasta) {
                $query->whereBetween('fecha', [$fechaDesde, $fechaHasta]);
            })
                ->orderBy('fecha')
                ->orderBy('hora')
                ->get();

            $pdf = Pdf::loadView('reports.all_geolocation', compact('users', 'locations'));
            return $pdf->stream("Reporte_Todos.pdf");
        }
    }
}
