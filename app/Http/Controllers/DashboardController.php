<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Insurer;
use App\Models\Inability;
use App\Models\User;

class DashboardController extends Controller
{
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
