<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eps;

class EpsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $epss = Eps::orderBy('status', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->paginate();

        return view('general.eps.index', compact('epss'));
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
        // Validación del formulario por backend
        $rules = [
            'name' => 'required',
        ];
        $messages = [
            'name.required' => 'El campo nombre es obligatorio.',
        ];
        $this->validate($request, $rules, $messages);

        $eps = new Eps();
        $eps->name = $request->name;
        $eps->status = 1; //Activo 1, Inactivo 2
        $eps->save();

        return redirect()->route('eps.index')->with('success', 'La eps ha sido creada correctamente.');
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
        // Validación del formulario por backend
        $rules = [
            'name' => 'required',
        ];
        $messages = [
            'name.required' => 'El campo nombre es obligatorio.',
        ];
        $this->validate($request, $rules, $messages);

        $eps = Eps::find($id);
        $eps->name = $request->name;
        $eps->status = 1; //Activo 1, Inactivo 2
        $eps->save();

        return redirect()->route('eps.index')->with('success', 'La eps ha sido actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $eps = Eps::find($id);
        $eps->delete();

        return redirect()->route('eps.index')->with('success', 'Excelente!!, La eps ha sido eliminada.');
    }
}
