<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bank;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banks = Bank::orderBy('status', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->paginate();

        return view('general.banks.index', compact('banks'));
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

        $bank = new Bank();
        $bank->name = $request->name;
        $bank->type_entity = $request->type_entity;
        $bank->area = $request->area;
        $bank->official_in_charge = $request->official_in_charge;
        $bank->employment = $request->employment;
        $bank->phone = $request->phone;
        $bank->mobile = $request->mobile;
        $bank->email = $request->email;
        $bank->address = $request->address;
        $bank->status = 1; //Activo 1, Inactivo 2
        $bank->save();

        return redirect()->route('bancos.index')->with('success', 'Excelente!!, El banco ha sido creado.');
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
        $rules = $this->getValidationRules();
        $messages = $this->getValidationMessages();

        $request->validate($rules, $messages);

        $bank = new Bank();
        $bank->name = $request->name;
        $bank->type_entity = $request->type_entity;
        $bank->area = $request->area;
        $bank->official_in_charge = $request->official_in_charge;
        $bank->employment = $request->employment;
        $bank->phone = $request->phone;
        $bank->mobile = $request->mobile;
        $bank->email = $request->email;
        $bank->address = $request->address;
        $bank->status = 1; //Activo 1, Inactivo 2
        $bank->save();

        return redirect()->route('bancos.index')->with('success', 'Excelente!!, El banco ha sido actualizado.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bank = Bank::find($id);
        $bank->status = 0;
        $bank->save();

        return redirect()->route('bancos.index')->with('success', 'El banco ha sido inactivado correctamente.');
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
