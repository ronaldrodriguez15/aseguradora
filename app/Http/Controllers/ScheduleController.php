<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $schedule = Schedule::latest()->first();

        return view('general.horario.index', compact('schedule'));
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
        $data = $request->validate([
            'dia1' => 'nullable|string',
            'dia2' => 'nullable|string',
            'hora_inicio' => 'nullable|string',
            'hora_final' => 'nullable|string',
            'festivos' => 'nullable|boolean',
        ]);

        $data['dia1'] = $data['dia1'] ?? 'Lunes';
        $data['dia2'] = $data['dia2'] ?? 'Viernes';
        $data['hora_inicio'] = $data['hora_inicio'] ?? '07:00';
        $data['hora_final'] = $data['hora_final'] ?? '05:00';
        $data['festivos'] = $data['festivos'] ?? 1;

        Schedule::create($data);

        return redirect()->back()->with('success', 'Horario establecido con éxito.');
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
