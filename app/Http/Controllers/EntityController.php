<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entity;

class EntityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $entities = Entity::orderBy('status', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->get();

        return view('general.entities.index', compact('entities'));
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


        $entity = new Entity();
        $entity->name = $request->name;
        $entity->nemo = $request->nemo;
        $entity->cnitpagador = $request->cnitpagador;
        $entity->sucursal = $request->sucursal;
        $entity->status = 1; //Activo 1, Inactivo 2
        $entity->save();

        return redirect()->route('entidades.index')->with('success', 'Excelente!!, La entidad ha sido creada.');
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
        $entity = Entity::find($id);

        return view('general.entities.edit', compact('entity'));
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

        $entity = Entity::find($id);
        $entity->cnitpagador = $request->cnitpagador;
        $entity->sucursal = $request->sucursal;
        $entity->status = 1; //Activo 1, Inactivo 2
        $entity->save();

        return redirect()->route('entidades.index')->with('success', 'El registro ha sido editado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $entity = Entity::find($id);
        $entity->delete();

        return redirect()->route('entidades.index')->with('success', 'La entidad ha sido eliminada.');
    }

    private function getValidationRules()
    {
        return [
            'name' => 'required|string',
            'nemo' => 'required|string',
            'cnitpagador' => 'required',
            'sucursal' => 'required',
        ];
    }

    private function getValidationMessages()
    {
        return [
            'name.required' => 'El campo Nombres es obligatorio.',
            'type_entity.required' => 'El campo Abreviatura es obligatorio.',
            'area.required' => 'El campo NIT o código es obligatorio.',
            'sucursal.required' => 'La sucursal es obligatoria.',
        ];
    }
}
