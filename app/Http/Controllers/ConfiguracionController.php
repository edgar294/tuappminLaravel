<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/** */
use App\Models\ConjuntoInformacion;

class ConfiguracionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permisosRoles:2,3');
    }

    public function index()
    {
        /** Obtenemos la casa o el apartamento principal */
        $owner_id = null;
        if(Auth::user()->rol_id == 2){
            $owner_id = Auth::id();
        }
        else{
            $owner_id = Auth::user()->owner_id;
        }

        $informacion = ConjuntoInformacion::where('conjunto_id', $owner_id)->first();

        return view('pages.configuracion.index', compact('informacion'));
    }

    public function save(Request $request)
    {
        /** Obtenemos la casa o el apartamento principal */
        $owner_id = null;
        if(Auth::user()->rol_id == 2){
            $owner_id = Auth::id();
        }
        else{
            $owner_id = Auth::user()->owner_id;
        }

        $informacion = ConjuntoInformacion::where('conjunto_id', $owner_id)->first();

        /** Si no existe el registto en la tabla conjuntos informaciones, creamos un nuevo registro */
        if(!$informacion){
            $data = $request->all();
            $data['conjunto_id'] = $owner_id;
            ConjuntoInformacion::create($data);
        }
        else{
            /** Si ya existe un registro para este conjunto actualizamos la información */
            $informacion->update($request->all());
        }

        return redirect()->back()->with('success', 'Datos guardados correctamente.');
    }
}
