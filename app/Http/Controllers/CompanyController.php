<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies = Company::orderBy('status', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->paginate();

        return view('general.companies.index', compact('companies'));
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

        $city = new Company();
        $city->name = $request->name;
        $city->status = 1; //Activo 1, Inactivo 2
        $city->save();

        return redirect()->route('empresas.index')->with('success', 'Excelente!!, La empresa ha sido creada.');
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

        $city = Company::find($id);
        $city->name = $request->name;
        $city->status = 1; //Activo 1, Inactivo 2
        $city->save();

        return redirect()->route('empresas.index')->with('success', 'El registro ha sido editado.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $city = Company::find($id);
        $city->delete();

        return redirect()->route('ciudades.index')->with('success', 'Excelente!!, La empresa ha sido eliminada.');
    }
}
