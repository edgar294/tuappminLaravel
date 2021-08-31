<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notificacion;

class NotificacionController extends Controller
{
    public function __construct()
    {
        $this->middleware('notificacion');
    }

    public  function index()
    {
        return view('pages.notificacion.index');
    }

    public function get_all_paginate(Request $request)
    {
        if(Auth::user()->rol_id == 1 || Auth::user()->rol_id == 2){
            $owner_id = Auth::id();
        }
        else{
            $owner_id = Auth::user()->owner_id;
        }

        $notificaciones = Notificacion::from('notificaciones as n')
        ->join('users as u', 'u.id', '=', 'n.created_by_id');

        /** Filtramos por nombre */
        if($request->name){
            $notificaciones->where('n.texto', 'like', '%' . $request->name . '%');
        }        

        $notificaciones->where('n.owner_id', $owner_id)
        ->select([
            'n.*', 'u.name as user'
        ])
        ->orderBy('n.id', 'ASC');

        $notificaciones = $notificaciones->paginate(30);

        return response()->json(compact('notificaciones'), 201);
    }   

    public function store(Request $request)
    {
        if(Auth::user()->rol_id == 1 || Auth::user()->rol_id == 2){
            $owner_id = Auth::id();
        }
        else{
            $owner_id = Auth::user()->owner_id;
        }

        $notificacion = new Notificacion;
        $notificacion->texto = $request->texto;
        $notificacion->owner_id = $owner_id;
        $notificacion->created_by_id = Auth::id();
        $notificacion->save();

        return response()->json('Notificación creada correctamente.', 201);
    }
    
    public function update(Request $request, $id)
    {
        $notificacion = Notificacion::find($id);
        $notificacion->texto = $request->texto;
        $notificacion->save();

        return response()->json('Notificación modificada correctamente.', 201);
    }

    public function destroy($id)
    {
        $notificacion = Notificacion::find($id);
        $notificacion->delete();

        return response()->json('Datos eliminados correctamente.', 201);
    }
}
