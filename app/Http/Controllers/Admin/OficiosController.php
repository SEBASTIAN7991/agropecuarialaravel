<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Oficios;
use App\Models\Admin\areas;
use App\Models\Admin\Personas;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\Datatables\DataTables;
use Validator;
use Storage;
use DB;

class OficiosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mis_areas=areas::select('id','Area')->get();
        $personas=Personas::select('id','Nombre')->get();
        return view('admin.oficios.index',compact('mis_areas','personas'));
    }

public function datatable(Request $request){
    if ($request->ajax()) {
            $mis_oficios = Oficios::select('id','Estatus','Oficio','Num_Oficio','Fecha','Descripcion',
            'Id_Area','Ubicacion_Archivo','Recibido_Por','Enviado_Por','created_at','updated_at')
            ->with('areas')
            ->with('personas');

            return DataTables()
            ->eloquent($mis_oficios)
            ->addColumn('action_Ofi', function($mis_oficios){
                    $button = '<button type="button" name="edit" id="'.$mis_oficios->id.'" class="edit btn btn-success"><i class="fas fa-fw fa-edit "></i></button>';
                    $button .= '<button type="button" id="'.$mis_oficios->id.'" descripcion="'.$mis_oficios->Descripcion.'" class="El_Ofi btn btn-danger"><i class="fas fa-fw fa-trash"></i></button>';//eliminar
                    if(empty($mis_oficios->Ubicacion_Archivo)){
                        $button .= '<button type="button" name="no_pdf" class="no_pdf btn btn-warning"><i class="fas fa-fw fa-file"></i></button>';
                    }else{
                    $button .= '<button type="button" name="ver_pdf_ofi" id="'.$mis_oficios->id.'" class="ver_pdf_ofi btn btn-dark"><i class="fas fa-fw fa-file-pdf"></i></button>';//ver pdf
                    }
                    return $button;
                })
            ->rawColumns(['action_Ofi'])
            ->toJson();

        }
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

    public function pdf($id)
    {
        if(request()->ajax()){
            $ofi_pdf= Oficios::findOrFail($id);
            return response()->json(['result'=>$ofi_pdf]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $ultimoid = Oficios::max('id');

        if($ultimoid==null){
            $ultimoid=0;
        }if($ultimoid>=1){
            $ultimoid=$ultimoid;
        }
        //====ver si hay algun archivo seleccionado
        if($request->hasFile('Ubicacion_Archivo')){//hay un archivo seleccionado
            $fileNameWithExt = $request->file('Ubicacion_Archivo')->getClientOriginalName();
            $path = $request->file('Ubicacion_Archivo')->storeAs('public/Documentos_Oficios/'.$ultimoid+1,$fileNameWithExt);

        }else{
            $fileNameWithExt='';
        }
        //======llenar dato de numero de oficio asi "moc/ca/xx/2022"
        $estatus=$request->Estatus;
        $oficio='';
        $recibido_por='';
        $enviado_por='';
        $num_ofi='';
        if($estatus=='Enviado' || $estatus=='Cancelado'){
            $oficio='MOC/CA/'.$request->Oficio.'/2022';
            $num_ofi=$request->Oficio;
            $recibido_por='0';
            $enviado_por=$request->Enviado_Por;
        }
        if($estatus=='Recibido'){
            $oficio=$request->Oficio2;
            $num_ofi='0';
            $recibido_por=$request->Recibido_Por;
            $enviado_por='0';
        }
        //=========ejecutar el llenado y guardar el archivo
        $form_data = array(
            'Estatus' => $request->Estatus,
            'Oficio' => $oficio,
            'Num_Oficio' => $num_ofi,
            'Fecha' => $request->Fecha,
            'Descripcion' => $request->Descripcion,
            'Id_Area' => $request->Id_Area,
            'Ubicacion_Archivo' => $fileNameWithExt,
            'Recibido_Por' => $recibido_por,
            'Enviado_Por' => $enviado_por,
            'Id_Usuario' => auth()->user()->id
        );
        Oficios::insert($form_data);//primera llamar el modelo creado
        
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
            $ofi_editar= Oficios::findOrFail($id);
            return response()->json(['result'=>$ofi_editar]);
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
        $pdfviejo=$request->Archivo_Viejo;
        $id=$request->hidden_id;
        //=================verificar si hizo cambio de archivo================
        //====================================================================
        if($pdfviejo!=""){//hay archivo cuando registro
            if($request->hasFile('Ubicacion_Archivo')){// si hay otro archivo seleccionado
                if(Storage::exists('public/Documentos_Oficios/'.$id.'/'.$pdfviejo)){
                    //$path = $request->file('Ubicacion_Archivo')->storeAs('public/Documentos_Oficios/'.$ultimoid+1,$fileNameWithExt);
                     Storage::delete('public/Documentos_Oficios/'.$id.'/'.$pdfviejo);
                     $miarchivo = $request->file('Ubicacion_Archivo')->getClientOriginalName();
                     $path = $request->file('Ubicacion_Archivo')->storeAs('public/Documentos_Oficios/'.$id,$miarchivo);
                }
            }
            else{
                $miarchivo=$pdfviejo;
            }
        }else{//no guardo archivo al registrar
            if($request->hasFile('Ubicacion_Archivo')){//no guardo archivo al registrar, pero ahora ya guardo
                $miarchivo = $request->file('Ubicacion_Archivo')->getClientOriginalName();
                $path = $request->file('Ubicacion_Archivo')->storeAs('public/Documentos_Oficios/'.$id,$miarchivo);
            }else{
                $miarchivo='';
            }
        }
        //==============================================================
        //======llenar dato de numero de oficio asi "moc/ca/xx/2022"
        $estatus=$request->Estatus;
        $oficio='';
        $recibido_por='';
        $enviado_por='';
        $num_ofi='';
        if($estatus=='Enviado' || $estatus=='Cancelado'){
            $oficio='MOC/CA/'.$request->Oficio.'/2022';
            $num_ofi=$request->Oficio;
            $recibido_por='0';
            $enviado_por=$request->Enviado_Por;
        }
        if($estatus=='Recibido'){
            $oficio=$request->Oficio2;
            $num_ofi='0';
            $recibido_por=$request->Recibido_Por;
            $enviado_por='0';
        }
        //=========ejecutar el llenado y guardar el archivo
        $form_data = array(
            'Estatus' => $request->Estatus,
            'Oficio' => $oficio,
            'Num_Oficio' => $num_ofi,
            'Fecha' => $request->Fecha,
            'Descripcion' => $request->Descripcion,
            'Id_Area' => $request->Id_Area,
            'Ubicacion_Archivo' => $miarchivo,
            'Recibido_Por' => $recibido_por,
            'Enviado_Por' => $enviado_por,
            'Id_Usuario' => auth()->user()->id
        );
        Oficios::whereId($request->hidden_id)->update($form_data);
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
        $data = Oficios::findOrFail($id);
        $archivo=$data->Ubicacion_Archivo;
        if($archivo==''){
        }if($archivo!=''){
            if(Storage::exists('public/Documentos_Oficios/'.$id.'/'.$archivo)){
                Storage::delete('public/Documentos_Oficios/'.$id.'/'.$archivo);
                Storage::deleteDirectory('public/Documentos_Oficios/'.$id);
            }
        }
    }
}
