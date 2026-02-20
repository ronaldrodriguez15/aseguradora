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
        // if (!$this->checkAccessBySchedule()) {
        //     return back()->withErrors([
        //         'email' => 'Acceso restringido según la configuración de horarios.',
        //     ]);
        // }

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
        $schedule = Schedule::first();

        // Si no hay configuración, dejamos entrar
        if (!$schedule) {
            return true;
        }

        $now = Carbon::now();

        // Día actual como número (0=domingo, 6=sábado)
        $diaHoy = $now->dayOfWeek;

        $horaActual = $now->format('H:i:s');

        // Mapa de días en texto → número
        $mapDias = [
            'domingo' => 0,
            'lunes' => 1,
            'martes' => 2,
            'miercoles' => 3,
            'miércoles' => 3,
            'jueves' => 4,
            'viernes' => 5,
            'sabado' => 6,
            'sábado' => 6,
        ];

        $dia1 = strtolower($schedule->dia1);
        $dia2 = strtolower($schedule->dia2);

        // Obtener posiciones numéricas
        $posDia1 = $mapDias[$dia1] ?? null;
        $posDia2 = $mapDias[$dia2] ?? null;

        // Validación de días
        $enRangoDias = false;
        if ($posDia1 !== null && $posDia2 !== null) {
            if ($posDia1 <= $posDia2) {
                $enRangoDias = ($diaHoy >= $posDia1 && $diaHoy <= $posDia2);
            } else {
                // Cruza semana (ej: viernes a martes)
                $enRangoDias = ($diaHoy >= $posDia1 || $diaHoy <= $posDia2);
            }
        }

        // Validación de horas
        $enRangoHoras = (
            $horaActual >= $schedule->hora_inicio &&
            $horaActual <= $schedule->hora_final
        );

        return $enRangoDias && $enRangoHoras;
    }
}
