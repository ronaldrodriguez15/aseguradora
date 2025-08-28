<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Schedule; 

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Redirección después del login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Crear nueva instancia del controlador.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Sobrescribimos el login para validar el horario antes de autenticar.
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // Verificar horario
        if (!$this->checkAccessBySchedule()) {
            return back()->withErrors([
                'email' => 'Acceso restringido según la configuración de horarios.',
            ]);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Validar acceso según la tabla horario.
     */
    protected function checkAccessBySchedule()
    {
        $schedule = Schedule::first(); // Asumo que solo tienes un registro

        if (!$schedule) {
            return true; // Si no hay configuración, dejamos entrar
        }

        $now = Carbon::now();
        $diaActual = strtolower($now->locale('es')->dayName); // lunes, martes, etc.
        $horaActual = $now->format('H:i:s');

        $dia1 = strtolower($schedule->dia1);
        $dia2 = strtolower($schedule->dia2);

        $diasSemana = ['domingo','lunes','martes','miércoles','jueves','viernes','sábado'];

        $posDia1 = array_search($dia1, $diasSemana);
        $posDia2 = array_search($dia2, $diasSemana);
        $posHoy  = array_search($diaActual, $diasSemana);

        // Validación de días
        $enRangoDias = false;
        if ($posDia1 !== false && $posDia2 !== false && $posHoy !== false) {
            if ($posDia1 <= $posDia2) {
                $enRangoDias = ($posHoy >= $posDia1 && $posHoy <= $posDia2);
            } else {
                // Caso cuando cruza semana (ej: viernes a martes)
                $enRangoDias = ($posHoy >= $posDia1 || $posHoy <= $posDia2);
            }
        }

        // Validación de horas
        $enRangoHoras = ($horaActual >= $schedule->hora_inicio && $horaActual <= $schedule->hora_final);

        return $enRangoDias && $enRangoHoras;
    }
}
