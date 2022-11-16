<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Visitas;
use App\Models\Admin\Localidades;
use App\Models\Admin\Cargos;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\Datatables\DataTables;
use Validator;

class VisitasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $Visitas = Visitas::select('id','Id_Cargo','Nombre','Paterno','Materno','Id_Loc','Telefono','Fecha_Visita','Asunto')
            ->with('cargos')
            ->with('localidades');

            return DataTables()
            ->eloquent($Visitas)
            ->addColumn('action_Vis', function($Visitas){
                    $button = '<button type="button" name="edit" id="'.$Visitas->id.'" class="edit btn btn-success"><i class="fas fa-fw fa-edit "></i></button>';
                    $button .= '<button type="button" name="el_vis" id="'.$Visitas->id.'" asunto="'.$Visitas->Asunto.'" class="el_vis btn btn-danger"><i class="fas fa-fw fa-trash"></i></button>';//eliminar
                    return $button;
                })
            ->rawColumns(['action_Vis'])
            ->toJson();
        }
        $cargos=Cargos::select('id','Nombre_Cargo')->get();
        $localidades=Localidades::select('id','Nom_Loc','Id_Reg')->get();
        return view('admin.visitas.index',compact('cargos','localidades'));
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
        $rules = array(
            'Nombre'    =>  'required',
            'Paterno'    =>  'required',
            'Materno'    =>  'required',
            'Hora_Ingreso'    =>  'required'
        );
        $error = Validator::make($request->all(), $rules);
        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'Nombre'        =>  $request->Nombre,
            'Paterno'        =>  $request->Paterno,
            'Materno'        =>  $request->Materno,
            'Id_Cargo'        =>  $request->Id_Cargo,
            'Id_Loc'        =>  $request->Id_Loc,
            'Id_Reg'        =>  $request->Id_Reg,
            'Nom_Org'        =>  $request->Nom_Org,
            'Telefono'        =>  $request->Telefono,
            'Fecha_Visita'        =>  $request->Fecha_Visita,
            'Hora_Ingreso'        =>  $request->Hora_Ingreso,
            'Asunto'        =>  $request->Asunto
        );
        Visitas::insert($form_data);//primera llamar el modelo creado
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
            $vis_editar= Visitas::findOrFail($id);
            return response()->json(['result'=>$vis_editar]);
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
            'Nombre'        =>  $request->Nombre,
            'Paterno'        =>  $request->Paterno,
            'Materno'        =>  $request->Materno,
            'Id_Cargo'        =>  $request->Id_Cargo,
            'Id_Loc'        =>  $request->Id_Loc,
            'Id_Reg'        =>  $request->Id_Reg,
            'Nom_Org'        =>  $request->Nom_Org,
            'Telefono'        =>  $request->Telefono,
            'Fecha_Visita'        =>  $request->Fecha_Visita,
            'Hora_Ingreso'        =>  $request->Hora_Ingreso,
            'Asunto'        =>  $request->Asunto
        );
 
        Visitas::whereId($request->hidden_id)->update($form_data);
 
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
        $data = Visitas::findOrFail($id);
        $data->delete();
    }
}
