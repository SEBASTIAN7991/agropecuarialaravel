<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Fierro;
use App\Models\Admin\Localidades;
use Illuminate\Support\Facades\Hash;
use Yajra\Datatables\DataTables;
use Validator;
use Storage;

class FierrosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $Fierros = Fierro::select('id','Fecha_Tramite','Id_Loc','Id_Reg','Nombre','Paterno','Materno','Curp','Tipo_Tramite')
            ->with('localidades')
            ->with('regiones');

            return DataTables()
            ->eloquent($Fierros)
            ->addColumn('action_Fierro', function($Fierros){
                    $button = '<button type="button" name="edit" id="'.$Fierros->id.'" class="edit btn btn-success"><i class="fas fa-fw fa-edit "></i></button>';
                    $button .= '<button type="button" name="el_fierro" id="'.$Fierros->id.'" nombre="'.$Fierros->Nombre.'" paterno="'.$Fierros->Paterno.'" materno="'.$Fierros->Materno.'" curp="'.$Fierros->Curp.'" class="el_fierro btn btn-danger"><i class="fas fa-fw fa-trash"></i></button>';//eliminar
                    return $button;
                })
            ->rawColumns(['action_Fierro'])
            ->toJson();
        }
        $localidades=Localidades::select('id','Nom_Loc','Id_Reg')->get();
        return view('admin.fierros.index',compact('localidades'));
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
        $form_data = array(
            'Fecha_Tramite'        =>  $request->Fecha_Tramite,
            'Elaborado_Por'        =>  $request->Elaborado_Por,
            'Id_Loc'        =>  $request->Id_Loc,
            'Id_Reg'        =>  $request->Id_Reg,
            'Nombre'        =>  $request->Nombre,
            'Paterno'        =>  $request->Paterno,
            'Materno'        =>  $request->Materno,
            'Curp'        =>  $request->Curp,
            'Rfc'        =>  $request->Rfc,
            'Edad'        =>  $request->Edad,
            'Sexo'        =>  $request->Sexo,
            'Tipo_Tramite'        =>  $request->Tipo_Tramite,
            'Upp'        =>  $request->Upp,
            'Folio_Pago'        =>  $request->Folio_Pago,
            'Total'        =>  $request->Total,
            'Estatus'        =>  $request->Estatus,
            'Id_Usuario'        =>  auth()->user()->id
        );
        Fierro::insert($form_data);//primera llamar el modelo creado
        return response()->json(['success' => 'Guardado']);
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
        if(request()->ajax()){
            $fierro_editar= Fierro::findOrFail($id);
            return response()->json(['result'=>$fierro_editar]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $form_data = array(
            'Fecha_Tramite'        =>  $request->Fecha_Tramite,
            'Elaborado_Por'        =>  $request->Elaborado_Por,
            'Id_Loc'        =>  $request->Id_Loc,
            'Id_Reg'        =>  $request->Id_Reg,
            'Nombre'        =>  $request->Nombre,
            'Paterno'        =>  $request->Paterno,
            'Materno'        =>  $request->Materno,
            'Curp'        =>  $request->Curp,
            'Rfc'        =>  $request->Rfc,
            'Edad'        =>  $request->Edad,
            'Sexo'        =>  $request->Sexo,
            'Tipo_Tramite'        =>  $request->Tipo_Tramite,
            'Upp'        =>  $request->Upp,
            'Folio_Pago'        =>  $request->Folio_Pago,
            'Total'        =>  $request->Total,
            'Estatus'        =>  $request->Estatus,
            'Id_Usuario'        =>  auth()->user()->id
        );
        Fierro::whereId($request->hidden_id)->update($form_data);
        return response()->json(['success' => 'Actualizado']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Fierro::findOrFail($id);
        $data->delete();
    }
}
