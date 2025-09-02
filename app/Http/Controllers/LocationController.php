<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LocationController extends Controller
{
    // Muestra la vista del módulo
    public function index()
    {
        return view('geolocation.index'); // tu blade se llama geolocalizacion.blade.php
    }

    // Guarda la ubicación del usuario
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

        if ($user->role === 'Administrador') {
            // Admin ve a todos
            $users = User::select('id', 'name', 'latitude', 'longitude', 'document')->get();
        } elseif ($user->role === 'Ventas') {
            // Ventas solo se ve a sí mismo
            $users = User::select('id', 'name', 'latitude', 'longitude', 'document')
                ->where('id', $user->id)
                ->get();
        } elseif ($user->role === 'Jefe de ventas') {
            // Decodificar vendedores asignados
            $ids = json_decode($user->vendedores_id, true);

            if (!empty($ids) && is_array($ids)) {
                // Incluir al jefe + sus vendedores asignados
                $ids[] = $user->id;
                $users = User::select('id', 'name', 'latitude', 'longitude', 'document')
                    ->whereIn('id', $ids)
                    ->get();
            } else {
                // Si no tiene vendedores asignados, solo se ve él
                $users = User::select('id', 'name', 'latitude', 'longitude', 'document')
                    ->where('id', $user->id)
                    ->get();

                // Mandamos alerta
                return response()->json([
                    'users' => $users,
                    'message' => 'No tienes vendedores asignados, habla con el administrador para que te asignen al menos uno.'
                ]);
            }
        } else {
            // Cualquier otro rol desconocido → se ve solo él mismo
            $users = User::select('id', 'name', 'latitude', 'longitude', 'document')
                ->where('id', $user->id)
                ->get();
        }

        return response()->json(['users' => $users]);
    }
}
