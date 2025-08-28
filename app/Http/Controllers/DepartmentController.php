<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Departments;
use Illuminate\Support\Facades\Log;

class DepartmentController extends Controller
{
    public function getDepartmentsByCity($city_id)
    {
        $departments = Departments::where('city_id', $city_id)->get();
    
        Log::debug('Debug message: ' . $city_id);
    
        if ($departments->isEmpty()) {
            return response()->json(['message' => 'No hay departamentos disponibles para esta ciudad.'], 404);
        }
    
        return response()->json($departments);
    }

}
