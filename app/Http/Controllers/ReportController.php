<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;
use App\Models\Asesor;
use App\Models\Insurer;
use App\Models\Entity;
use App\Models\Inability;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $inabilities = Inability::select('inabilities.*', 'insurers.name as insurer_name', DB::raw('
                CASE
                    WHEN path_estasseguro IS NULL AND path_aseguradora IS NULL AND path_pago IS NULL THEN "Sin firmar"
                    WHEN path_estasseguro IS NOT NULL AND path_aseguradora IS NOT NULL AND path_pago IS NOT NULL THEN "Pendiente"
                    WHEN (SELECT COUNT(*) FROM documents_signed WHERE inability_id = inabilities.id) >= 3 THEN "Firmado"
                    ELSE "En espera"
                END as estado_firmado
            '))
            ->leftJoin('insurers', 'inabilities.insurer_id', '=', 'insurers.id')
            ->orderBy('status', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->paginate();


        $cities = City::where('status', 1)->get();
        $asesors = Asesor::where('status', 1)->get();
        $insurers = Insurer::where('status', 1)->get();
        $entities = Entity::where('status', 1)->get();

        return view('reports.index', compact('inabilities', 'cities', 'asesors', 'insurers', 'entities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
