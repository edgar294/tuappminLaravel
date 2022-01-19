<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

/** Models */
use App\Models\User;
use App\Models\ResidenteConjunto;
use App\Models\ResidenteInformacion;

class ResidenteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permisosRoles:1,2,3')->only([
            'get_all_paginate', 'index'
        ]);
        $this->middleware('permisosRoles:2,3')->only([
            'store', 'update', 'destroy'
        ]);
    }

    public  function index()
    {
        /** Obtenemos la casa o el apartamento principal */
        $owner_id = null;
        if(Auth::user()->rol_id == 2){
            $owner_id = Auth::id();
        }
        else{
            $owner_id = Auth::user()->owner_id;
        }

        if($owner_id){
            $data['residencia'] = User::whereId($owner_id)->select('tipo')->first();
        }
        else{
            $residencia = new \stdClass;
            $residencia->tipo = null;
            $data['residencia'] = json_encode($residencia);
        }

        return view('pages.residente.index', compact('data'));
    }

    public function get_all_paginate(Request $request)
    {
        $users = ResidenteConjunto::join('users as u', 'u.id', '=', 'residentes_conjuntos.user_id')
        ->join('users as us', 'us.id', '=', 'residentes_conjuntos.conjunto_id')
        ->join('residentes_informaciones as ri', 'ri.residente_id', '=', 'residentes_conjuntos.id')
        ->where('u.rol_id', 5);

        if(Auth::user()->rol_id == 2 || Auth::user()->rol_id == 3){
            /** Obtenemos el propietario principal */
            if(Auth::user()->rol_id == 2){
                $owner_id = Auth::id();
            }
            else{
                $owner_id = Auth::user()->owner_id;
            }

            $users->where('residentes_conjuntos.conjunto_id', $owner_id);
        }

        /** Filtramos por id */
        if($request->id){
            $users->where('u.id', $request->id);
        }

        /** Filtramos por nombre */
        if($request->name){
            $users->where('u.name', 'like', '%' . $request->name . '%');
        }

        $users->select([
            'residentes_conjuntos.*', 'u.name', 'us.name as residencia',
            'ri.bloque', 'ri.apartamento', 'ri.nombre_propietario',
            'ri.telefono as info_telefono', 'ri.numero_casa', 'u.email',
            'u.telefono'
        ])
        ->orderBy('u.id', 'ASC');

        $users = $users->paginate(30);

        return response()->json(compact('users'), 201);
    }

    public function store(Request $request)
    {
        /** Verificamos si el email ya esta registrado */
        $user = User::where('email', $request->email)->first();

        if($user && !$request->save_force){
            return response()->json('Email existente', 202);
        }

        /** Validacion de datos */
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => !$request->save_force ? ['required', 'string', 'email', 'max:255', 'unique:users'] : [],
            'password' => 'required|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 200);
        }

        if(!$request->save_force){
            $user = $request->except([
                'bloque', 'numero_casa', 'apartamento',
                'nombre_propietario', 'info_telefono'
            ]);
    
            /** Encriptamos la contraseña */
            $user['password'] = bcrypt($user['password']);
    
            $user['created_by_id'] = Auth::id();
            $user['rol_id'] = 5;
    
            /** Creamos el usuario */
            $user = User::create($user);
        }

        /** Obtenemos el propietario principal */
        if(Auth::user()->rol_id == 2){
            $owner_id = Auth::id();
        }
        else{
            $owner_id = Auth::user()->owner_id;
        }

        /** Asociamos y guardamos el residente con la residencia */
        $residente_conjunto = new ResidenteConjunto;
        $residente_conjunto->user_id = $user->id;
        $residente_conjunto->conjunto_id = $owner_id;
        $residente_conjunto->created_by_id = Auth::id();
        $residente_conjunto->save();

        /** Guardamos la informacion adicional del residente para la habitacion */
        $informacion = $request->only([
            'bloque', 'apartamento', 'nombre_propietario',
            'info_telefono', 'numero_casa'
        ]);

        $informacion['telefono'] = $informacion['info_telefono'];
        $informacion['residente_id'] = $residente_conjunto->id;

        ResidenteInformacion::create($informacion);

        return response()->json('Residente creado correctamente.', 201);
    }
    
    public function update(Request $request, $id)
    {
        $residente_conjunto = ResidenteConjunto::find($id);

        $user = User::findOrFail($residente_conjunto->user_id);

        /** Validacion de datos */
        $validator = Validator::make($request->all(), [
            'email' => $request->email != $user->email ? ['required', 'string', 'email', 'max:255', 'unique:users'] : '',
            'password' => $request->password ? 'required|confirmed|min:6' : '',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 200);
        }

        /** Actualizamos los datos del usuario */
        $user->name = $request->name;
        $user->email = $request->email;
        $user->telefono = $request->telefono;
        
        if($request->password){
            $user->password = bcrypt($request->password);
        }        

        $user->save();

        /** Actualizamos la informacion del residente */
        $informacion = $request->only([
            'bloque', 'apartamento', 'nombre_propietario',
            'info_telefono', 'numero_casa'
        ]);

        $informacion['telefono'] = $informacion['info_telefono'];

        ResidenteInformacion::where('residente_id', $id)->first()->update($informacion);

        return response()->json('Residente modificado correctamente.', 201);
    }

    public function destroy($id)
    {
        $residente_conjunto = ResidenteConjunto::find($id);
        $residente_conjunto->delete();

        return response()->json('Residente eliminado correctamente.', 201);
    }
}
