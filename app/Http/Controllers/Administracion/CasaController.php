<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/** Models */
use App\Models\Casa;

class CasaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('casa');
        $this->middleware('permisosRoles:2,3');
    }

    public function index()
    {
        return view('pages.administracion.casa.index');
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

        $casas = Casa::where('conjunto_id', $owner_id);

        /** Filtramos por número de casa */
        if($request->name){
            $casas->where('numero', $request->name);
        }

        $casas->orderBy('id', 'ASC');
        $casas = $casas->paginate(30);

        return response()->json(compact('casas'), 201);
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

        $casas = Casa::where('conjunto_id', $owner_id)
        ->orderBy('id', 'ASC')
        ->get();

        return response()->json(compact('casas'), 201);
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

        $casa = new Casa;
        $casa->numero = $request->numero;
        $casa->conjunto_id = $owner_id;
        $casa->save();

        return response()->json('Casa guardado', 201);
    }

    public function destroy($id)
    {
        $casa = Casa::find($id);
        $casa->delete();

        return response()->json('Casa eliminado', 201);
    }
}
