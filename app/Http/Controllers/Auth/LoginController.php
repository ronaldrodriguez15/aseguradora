<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Schedule;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        $schedule = Schedule::latest()->first();

        if (!$schedule) {
            return back()->withErrors([
                'email' => 'No hay un horario configurado para el acceso.',
            ]);
        }

        Carbon::setLocale('es');
        date_default_timezone_set('America/Bogota');

        $now = Carbon::now();
        $diaActual = ucfirst($now->dayName);

        $diasSemana = [
            "Lunes",
            "Martes",
            "Miércoles",
            "Jueves",
            "Viernes",
            "Sábado",
            "Domingo"
        ];

        $indexDia1 = array_search($schedule->dia1, $diasSemana);
        $indexDia2 = array_search($schedule->dia2, $diasSemana);
        $indexActual = array_search($diaActual, $diasSemana);

        $diaPermitido = false;
        if ($indexDia1 !== false && $indexDia2 !== false) {
            if ($indexDia1 <= $indexDia2) {
                $diaPermitido = $indexActual >= $indexDia1 && $indexActual <= $indexDia2;
            } else {
                $diaPermitido = $indexActual >= $indexDia1 || $indexActual <= $indexDia2;
            }
        }

        $horaInicio = Carbon::createFromFormat('H:i', $schedule->hora_inicio, 'America/Bogota');
        $horaFinal  = Carbon::createFromFormat('H:i', $schedule->hora_final, 'America/Bogota');

        $horaPermitida = $now->between($horaInicio, $horaFinal);

        $horaInicioStr = $horaInicio->format('g:i A');
        $horaFinalStr  = $horaFinal->format('g:i A');

        if (!$diaPermitido || !$horaPermitida) {
            return back()->withErrors([
                'email' => "Acceso restringido de {$schedule->dia1} a {$schedule->dia2}, entre {$horaInicioStr} y {$horaFinalStr}.",
            ]);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        return $this->sendFailedLoginResponse($request);
    }
}
