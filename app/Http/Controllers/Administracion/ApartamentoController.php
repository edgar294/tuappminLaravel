<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/** Models */
use App\Models\Apartamento;

class ApartamentoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('apartamento');
        $this->middleware('permisosRoles:2,3');
    }

    public function index()
    {
        return view('pages.administracion.apartamento.index');
    }

    public function get_all_paginate(Request $request)
    {
        /** Obtenemos propietario principal */
        if(Auth::user()->rol_id == 2){
            $owner_id = Auth::id();
        }
        else{
            $owner_id = Auth::user()->owner_id;
        }

        $apartamentos = Apartamento::where('conjunto_id', $owner_id);

        /** Filtramos por bloque o apartamento */
        if($request->name){
            $apartamentos->where('bloque', $request->name)
            ->orWhere('apartamento', $request->name);
        }
        
        $apartamentos->orderBy('id', 'ASC');
        $apartamentos = $apartamentos->paginate(30);

        return response()->json(compact('apartamentos'), 201);
    }

    public function get_all(Request $request)
    {
        /** Obtenemos propietario principal */
        if(Auth::user()->rol_id == 2){
            $owner_id = Auth::id();
        }
        else{
            $owner_id = Auth::user()->owner_id;
        }

        $apartamentos = Apartamento::where('conjunto_id', $owner_id);

        /** filtrmos por bloque */
        if($request->bloque){
            $apartamentos->where('bloque', $request->bloque);
        }

        $apartamentos->orderBy('id', 'ASC');
        $apartamentos = $apartamentos->get();

        return response()->json(compact('apartamentos'), 201);
    }

    public function store(Request $request)
    {
        /** Obtenemos propietario principal */
        if(Auth::user()->rol_id == 2){
            $owner_id = Auth::id();
        }
        else{
            $owner_id = Auth::user()->owner_id;
        }

        $apartamento = new Apartamento;
        $apartamento->bloque = $request->bloque;
        $apartamento->apartamento = $request->apartamento;
        $apartamento->conjunto_id = $owner_id;
        $apartamento->save();

        return response()->json('Apartamento guardado', 201);
    }

    public function destroy($id)
    {
        $apartamento = Apartamento::find($id);
        $apartamento->delete();

        return response()->json('Apartamento eliminado', 201);
    }
}
