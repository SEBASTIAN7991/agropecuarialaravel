<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Validaciones;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Admin\ReporteValidaciones;
use App\Models\User;
use App\Models\Admin\Solicitudes;
use App\Models\Admin\Proyectos;
use App\Models\Admin\Beneficiarios;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\Datatables\DataTables;
use Validator;
use Storage;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Font;
use Exception;

class ValidacionesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $mis_valid = Validaciones::select('id','Id_Sol','Id_Pro','Fecha_Val_Inicio','Cant_Validado','Ben_H_Validado','Ben_M_Validado','updated_at')
            ->with('solicitudes')
            ->with('proyectos');

            return DataTables()
            ->eloquent($mis_valid)
            ->addColumn('action_Val', function($mis_valid){
                $button = '<button type="button" name="edit" id="'.$mis_valid->id.'" class="edit btn btn-success"><i class="fas fa-fw fa-edit "></i></button>';
                $button .= '<button type="button" name="elival" id="'.$mis_valid->id.'" representante="'.$mis_valid->solicitudes->Subrepresentante.'" class="elival btn btn-danger"><i class="fas fa-fw fa-trash"></i></button>';//eliminar
                    return $button;
                })
            ->rawColumns(['action_Val'])
            ->toJson();
        }
        $proyectos= Proyectos::select('id','Nom_Pro')
             ->where('Estatus',1)
             ->get();
        $mis_solicitudes = Solicitudes::select('id','Subrepresentante')->get();
        $mis_usuarios = User::select('id','name')->where('id','>',1)->get();
        return view('admin.validaciones.index',compact('mis_solicitudes','mis_usuarios','proyectos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function excel(Request $request){
        return (new ReporteValidaciones())->download('validaciones.xlsx');
    }

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
            'Subrepresentante'    =>  'required',
            'Resp_Valid'    =>  'required',
            'Cant_Validado'    =>  'required',
            'Ben_H_Validado'    =>  'required',
            'Ben_M_Validado'    =>  'required'
        );
        $error = Validator::make($request->all(), $rules);
        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }
        $form_data = array(
            'Id_Sol' => $request->Subrepresentante,
            'Id_Pro' => $request->Id_Pro,
            'Resp_Valid' => $request->Resp_Valid,
            'Fecha_Val_Inicio' => $request->Fecha_Val_Inicio,
            'Fecha_Val_Termino' => $request->Fecha_Val_Termino,
            'Cant_Validado' => $request->Cant_Validado,
            'Ben_H_Validado' => $request->Ben_H_Validado,
            'Ben_M_Validado' => $request->Ben_M_Validado,
            'Estatus' => $request->Estatus,
            'Verificado' => $request->Verificado,
            'Id_Usuario' => $request->Id_Usuario,
            'Tipo_Asignacion' => $request->Tipo_Asignacion,
            'Of_Control' => $request->Of_Control,
            'Fecha_Entrega' => $request->Fecha_Entrega,
            'Comentario' => $request->Comentario,
            'Porcentaje' => $request->Porcentaje
        );
        Validaciones::insert($form_data);//primera llamar el modelo creado

        $form_data_sol = array(
            'Validado'        =>  1
        );
        
        $FOLIO_SOL = Solicitudes::findOrFail($request->Subrepresentante);
        $mifolio=$FOLIO_SOL->Folio;

        Solicitudes::where('Folio',$mifolio)->update($form_data_sol);

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
            $mis_valid= Validaciones::select('id','Id_Sol','Id_Pro','Resp_Valid','Fecha_Val_Inicio','Fecha_Val_Termino','Cant_Validado','Ben_H_Validado','Ben_M_Validado','Estatus','Verificado','Id_Usuario','Tipo_Asignacion','Of_Control','Fecha_Entrega','Comentario','Porcentaje')
            ->with('solicitudes')
            ->findOrFail($id);
            return response()->json(['result'=>$mis_valid]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function mod_ben_val($id)
    {
        $form_data4 = array(
            'Estatus'        =>  0
        );

        $form_data3 = array(
            'Estatus2'        =>  0
        );

        if(request()->ajax()){
            Beneficiarios::where('Id_Val','=',$id)->update($form_data3);
            Validaciones::whereId($id)->update($form_data4);
            return response()->json(['success' => 'Actualizado']);
        }
    }

    public function update(Request $request)
    {
        $rules = array(
            'Subrepresentante'    =>  'required',
            'Fecha_Val_Inicio'    =>  'required',
            'Fecha_Val_Termino'    =>  'required',
            'Cant_Validado'    =>  'required',
            'Ben_H_Validado'    =>  'required',
            'Ben_M_Validado'    =>  'required'
        );
        $error = Validator::make($request->all(), $rules);
        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }
        $form_data = array(
            'Id_Sol' => $request->Subrepresentante,
            'Id_Pro' => $request->Id_Pro,
            'Resp_Valid' => $request->Resp_Valid,
            'Fecha_Val_Inicio' => $request->Fecha_Val_Inicio,
            'Fecha_Val_Termino' => $request->Fecha_Val_Termino,
            'Cant_Validado' => $request->Cant_Validado,
            'Ben_H_Validado' => $request->Ben_H_Validado,
            'Ben_M_Validado' => $request->Ben_M_Validado,
            'Estatus' => $request->Estatus,
            'Verificado' => $request->Verificado,
            'Id_Usuario' => $request->Id_Usuario,
            'Tipo_Asignacion' => $request->Tipo_Asignacion,
            'Of_Control' => $request->Of_Control,
            'Fecha_Entrega' => $request->Fecha_Entrega,
            'Comentario' =>  $request->Comentario,
            'Porcentaje' => $request->Porcentaje
        );
        $form_data2 = array(
            'Estatus2'        =>  $request->Estatus
        );

        Beneficiarios::where('Id_Val','=',$request->hidden_id)->update($form_data2);

        Validaciones::whereId($request->hidden_id)->update($form_data);

        
         $form_data_sol = array(
            'Validado'        =>  1
        );
        $FOLIO_SOL = Solicitudes::findOrFail($request->Subrepresentante);
        $mifolio=$FOLIO_SOL->Folio;
        Solicitudes::where('Folio',$mifolio)->update($form_data_sol);

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
        $data = Validaciones::findOrFail($id);
        $data->delete();
    }

    public function VerDatos($id)
    {
        if(request()->ajax()){
            $sol_sel= Solicitudes::select('Id_Org','Id_Loc','Id_Pro','Cant_Sol','Ben_H','Ben_M')
            ->with('organizaciones')
            ->with('localidades')
            ->with('proyectos')
            ->findOrFail($id);
            return response()->json(['result'=>$sol_sel]);
        }
    }
}
