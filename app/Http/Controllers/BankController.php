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
        // Validación del formulario por backend
        $rules = [
            'name' => 'required',
        ];
        $messages = [
            'name.required' => 'El campo nombre es obligatorio.',
        ];
        $this->validate($request, $rules, $messages);

        $bank = new Bank();
        $bank->name = $request->name;
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
        $bank = Bank::find($id);

        return view('general.banks.edit', compact('bank'));
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

        $bank = new Bank();
        $bank->name = $request->name;
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
}
