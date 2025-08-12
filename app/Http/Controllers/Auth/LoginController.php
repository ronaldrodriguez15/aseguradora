<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // Sobrescribimos el login para validar horario
    public function login(Request $request)
    {
        $now = Carbon::now();

        if ($now->isWeekend()) {
            return back()->withErrors([
                'email' => 'El acceso está restringido a lunes a viernes de 7:00 AM a 5:00 PM.',
            ]);
        }

        if ($now->hour < 7 || $now->hour >= 17) {
            return back()->withErrors([
                'email' => 'El acceso está restringido a lunes a viernes de 7:00 AM a 5:00 PM.',
            ]);
        }

        $this->validateLogin($request);

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        return $this->sendFailedLoginResponse($request);
    }
}
