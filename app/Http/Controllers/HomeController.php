<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inability;
use App\Models\Insurer;
use App\Models\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Contar los registros
        $totalAseguradoras = Insurer::count();
        $totalAfiliaciones = Inability::count();
        $totalUsuarios = User::count();

        // Retornar la vista con los datos
        return view('dashboard', compact('totalAseguradoras', 'totalAfiliaciones', 'totalUsuarios'));
    }
}
