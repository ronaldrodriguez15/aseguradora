<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuarios = User::orderBy('status', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->paginate(50);

        return view('users.index', compact('usuarios'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create');
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

        $user = new User();
        $user->name = $request->name;
        $user->document = $request->document;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->birthdate = $request->birthdate;
        $user->password = Hash::make($request->password);
        $user->status = 1; //1: Activo, 0: Inactivo
        $user->save();

        return redirect()->route('usuarios.index')->with('success', 'Excelente!!, El usuario ha sido creado.');
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
        $usuario = User::find($id);

        return view('users.edit', compact('usuario'));
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

        $user = User::find($id);
        $user->name = $request->get('name');
        $user->document = $request->get('document');
        $user->phone = $request->get('phone');
        $user->email = $request->get('email');
        $user->birthdate = $request->get('birthdate');
        $user->password = Hash::make($request->get('password'));
        $user->status = 1; //1: Activo, 0: Inactivo
        $user->save();

        return redirect()->route('usuarios.index')->with('success', 'Excelente!!, El usuario ha sido actualizado.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $user->status = 0;
        $user->save();

        return redirect()->route('usuarios.index')->with('success', 'Excelente!!, El usuario ha sido inactivado correctamente.');
    }

    private function getValidationRules()
    {
        return [
            'name' => 'required',
            'document' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'emailConfirm' => 'required|email|same:email',
            'birthdate' => 'required|date',
            'password' => 'required',
        ];
    }

    private function getValidationMessages()
    {
        return [
            'name.required' => 'El campo Nombres es obligatorio.',
            'document.required' => 'El campo Documento es obligatorio.',
            'phone.required' => 'El campo Celular es obligatorio.',
            'email.required' => 'El campo Correo electrónico es obligatorio.',
            'email.email' => 'El campo Correo electrónico debe ser una dirección de correo válida.',
            'emailConfirm.required' => 'El campo Confirmación correo electrónico es obligatorio.',
            'emailConfirm.email' => 'El campo Confirmación correo electrónico debe ser una dirección de correo válida.',
            'emailConfirm.same' => 'Los correos electrónicos no coinciden.',
            'birthdate.required' => 'El campo Nacimiento es obligatorio.',
            'birthdate.date' => 'El campo Nacimiento debe ser una fecha válida.',
            'password.required' => 'El campo Contraseña es obligatorio.',
        ];
    }
}
