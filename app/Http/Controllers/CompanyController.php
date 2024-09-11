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
        $rules = $this->getValidationRules();
        $messages = $this->getValidationMessages();

        $request->validate($rules, $messages);


        $company = new Company();
        $company->name = $request->name;
        $company->type_entity = $request->type_entity;
        $company->area = $request->area;
        $company->official_in_charge = $request->official_in_charge;
        $company->employment = $request->employment;
        $company->phone = $request->phone;
        $company->mobile = $request->mobile;
        $company->email = $request->email;
        $company->address = $request->address;
        $company->status = 1; //Activo 1, Inactivo 2
        $company->save();

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
        $company = Company::find($id);

        return view('general.companies.edit', compact('company'));
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
        $rules = $this->getValidationRules();
        $messages = $this->getValidationMessages();

        $request->validate($rules, $messages);

        $company = Company::find($id);
        $company->name = $request->name;
        $company->name = $request->name;
        $company->type_entity = $request->type_entity;
        $company->area = $request->area;
        $company->official_in_charge = $request->official_in_charge;
        $company->employment = $request->employment;
        $company->phone = $request->phone;
        $company->mobile = $request->mobile;
        $company->email = $request->email;
        $company->address = $request->address;
        $company->status = 1; //Activo 1, Inactivo 2
        $company->save();

        return redirect()->route('empresas.index')->with('success', 'El registro ha sido editado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $company = Company::find($id);
        $company->delete();

        return redirect()->route('ciudades.index')->with('success', 'La empresa ha sido eliminada.');
    }

    private function getValidationRules()
    {
        return [
            'name' => 'required|string|max:255',
            'type_entity' => 'required|string|max:255',
            'area' => 'required|string|max:255',
            'official_in_charge' => 'required|string|max:255',
            'employment' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'mobile' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:255',
        ];
    }

    private function getValidationMessages()
    {
        return [
            'name.required' => 'El campo Nombres es obligatorio.',
            'type_entity.required' => 'El campo Tipo de Entidad es obligatorio.',
            'area.required' => 'El campo Área es obligatorio.',
            'official_in_charge.required' => 'El campo Funcionario a Cargo es obligatorio.',
            'employment.required' => 'El campo Cargo es obligatorio.',
            'phone.required' => 'El campo Teléfono es obligatorio.',
            'mobile.required' => 'El campo Móvil es obligatorio.',
            'email.required' => 'El campo Correo electrónico es obligatorio.',
            'email.email' => 'El campo Correo electrónico debe ser una dirección de correo válida.',
            'address.required' => 'El campo Dirección es obligatorio.',
        ];
    }
}
