<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin\Solicitudes;
use App\Models\Admin\Beneficiarios;
use App\Models\User;
use App\Models\Admin\Localidades;
use App\Models\Admin\Regiones;
use App\Models\Admin\Proyectos;
use App\Models\Admin\Validaciones;
use App\Http\Controllers\Controller;
use Yajra\Datatables\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Admin\ExportBeneficiarios;
use Validator;
use Storage;
use DB;

class BeneficiariosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        //dd(auth()->user()->name,auth()->user()->id);
        $id_us=auth()->user()->id;
        if($id_us==1){
            $proyectos= Proyectos::select('id','Nom_Pro')
             ->where('Estatus',1)
             ->get();
             $mis_valid= validaciones::select('id','Id_Sol')
             ->with('solicitudes')
             ->get();
        }else{
            $proyectos= Proyectos::select('id','Nom_Pro')
             ->where('Estatus',1)
             ->get();
             $mis_valid= validaciones::select('id','Id_Sol')
             ->where('Estatus',1)
             ->where('Id_Usuario',auth()->user()->id)
             ->with('solicitudes')
             ->get();    
        }
        
         $localidades=Localidades::select('id','Nom_Loc','Id_Reg')->get();

         $localidades_buscar=Beneficiarios::select('id','Id_Loc')
         ->with('localidades')
         ->groupBy('Id_Loc')
         ->get();

         $regiones=Regiones::select('id','Nom_Reg')->get();

        return view('admin.beneficiarios.index', compact('mis_valid','localidades','localidades_buscar','proyectos','regiones'));
    }
    
    public function excel(Request $request){
        return Excel::download(new ExportBeneficiarios(), 'beneficiarios.xlsx');
    }

    public function datatable(Request $request){
    if ($request->ajax()) {
            $id_us=auth()->user()->id;
            if($id_us==1){
                $mis_ben = Beneficiarios::select('id','Id_Sol','Id_Val','Id_Pro','Nom_Ben','Pat_Ben','Mat_Ben','Clave_El','Curp','Id_Loc','Id_Reg','Estatus','Documentos','Id_Usuario','created_at')
                ->with('solicitudes')
                ->with('localidades')
                ->with('validaciones')
                ->with('proyectos')
                ->with('regiones')
                ->with('users');

                return DataTables()
                ->eloquent($mis_ben)
                ->addColumn('action_Ben', function($mis_ben){
                        $button = '<button type="button" name="edit" id="'.$mis_ben->id.'" class="edit btn btn-success"><i class="fas fa-fw fa-edit "></i></button>';
                        $button .= '<button type="button" name="El_Ben" id="'.$mis_ben->id.'" Nom_Ben="'.$mis_ben->Nom_Ben.'" Pat_Ben="'.$mis_ben->Pat_Ben.'" Mat_Ben="'.$mis_ben->Mat_Ben.'" Curp="'.$mis_ben->Curp.'" class="El_Ben btn btn-danger"><i class="fas fa-fw fa-trash"></i></button>';//eliminar
                        if(empty($mis_ben->Documentos)){
                            $button .= '<button type="button" name="sin_pdf" class="sin_pdf btn btn-warning"><i class="fas fa-fw fa-file"></i></button>';
                        }else{
                        $button .= '<button type="button" name="ver_pdf_ben" id="'.$mis_ben->id.'" class="ver_pdf_ben btn btn-dark"><i class="fas fa-fw fa-file-pdf"></i></button>';//ver pdf
                        }
                        return $button;
                    })
                ->rawColumns(['action_Ben'])
                ->filter(function($query) use ($request){
                    if($request->has('Id_Val') && !empty($request->get('Id_Val'))){
                        $query->where('Id_Val', $request->get('Id_Val'));
                    }
                    if($request->has('nombre') && !empty($request->get('nombre'))){
                        $query->where('Nom_Ben','LIKE','%' .$request->get('nombre').'%');
                    }
                    if($request->has('curp') && !empty($request->get('curp'))){
                        $query->where('Curp','LIKE','%' .$request->get('curp').'%');
                    }
                    if($request->has('clave') && !empty($request->get('clave'))){
                        $query->where('Clave_El','LIKE','%' .$request->get('clave').'%');
                    }
                    if($request->has('localidadbusca') && !empty($request->get('localidadbusca'))){
                        $query->where('Id_Loc',$request->get('localidadbusca'));
                    }
                    if($request->has('estatusbusca') && !empty($request->get('estatusbusca'))){
                        $query->where('Estatus',$request->get('estatusbusca'));
                    }
                })
                ->toJson();
            }else{
                $mis_Ben = Beneficiarios::select('id','Id_Sol','Id_Val','Id_Pro','Nom_Ben','Pat_Ben','Mat_Ben','Clave_El','Curp','Id_Loc','Id_Reg','Estatus','Documentos','Id_Usuario','created_at')
                ->where('Estatus2',1)
                ->where('Id_Usuario',auth()->user()->id)
                ->with('solicitudes')
                ->with('localidades')
                ->with('validaciones')
                ->with('proyectos')
                ->with('regiones')
                ->with('users');

                return DataTables()
                ->eloquent($mis_Ben)
                ->addColumn('action_Ben', function($mis_Ben){
                        $button = '<button type="button" name="edit" id="'.$mis_Ben->id.'" class="edit btn btn-success"><i class="fas fa-fw fa-edit "></i></button>';
                        /*$button .= '<button type="button" name="El_Ben" id="'.$mis_Ben->id.'" Nom_Ben="'.$mis_Ben->Nom_Ben.'" Pat_Ben="'.$mis_Ben->Pat_Ben.'" Mat_Ben="'.$mis_Ben->Mat_Ben.'" Curp="'.$mis_Ben->Curp.'" class="El_Ben btn btn-danger"><i class="fas fa-fw fa-trash"></i></button>';//eliminar*/
                        if(empty($mis_Ben->Documentos)){
                            $button .= '<button type="button" class="btn btn-warning"><i class="fas fa-fw fa-file"></i></button>';
                        }else{
                        $button .= '<button type="button" name="ver_pdf_ben" id="'.$mis_Ben->id.'" class="ver_pdf_ben btn btn-dark"><i class="fas fa-fw fa-file-pdf"></i></button>';//ver pdf
                        }
                        
                        return $button;
                    })
                ->rawColumns(['action_Ben'])
                ->filter(function($query) use ($request){
                    if($request->has('Id_Val') && !empty($request->get('Id_Val'))){
                        $query->where('Id_Val', $request->get('Id_Val'));
                    }
                    if($request->has('nombre') && !empty($request->get('nombre'))){
                        $query->where('Nom_Ben','LIKE','%' .$request->get('nombre').'%');
                    }
                    if($request->has('curp') && !empty($request->get('curp'))){
                        $query->where('Curp','LIKE','%' .$request->get('curp').'%');
                    }
                    if($request->has('clave') && !empty($request->get('clave'))){
                        $query->where('Clave_El','LIKE','%' .$request->get('clave').'%');
                    }
                })
                ->toJson();
            }
        }
}

/*public function datatable(Request $request){

//    dd($request->get('Id_Val'));
    dd($request->get('Id_Val'));
    $Mis_Ben = Beneficiarios::with(['solicitudes','localidades'])
                ->select('id','Id_Sol','Id_Val','Nom_Ben','Pat_Ben','Mat_Ben','Curp','Id_Loc','Estatus','updated_at');

    if ($request->ajax()) {
                return DataTables::of($Mis_Ben)
                ->addColumn('action_Ben', function($mis_Sol){
                    $button = '<button type="button" name="edit" class="edit btn btn-primary"><i class="fas fa-fw fa-edit "></i></button>';
                    $button .= '<button type="button" name="elisol" class="elisol btn btn-danger"><i class="fas fa-fw fa-trash"></i></button>';//eliminar
                    $button .= '<button type="button" name="ver_pdf_sol" class="ver_pdf_sol btn btn-secondary"><i class="fas fa-fw fa-file-pdf"></i></button>';
                    return $button;
                })
                ->rawColumns(['action_Ben'])
                ->filter(function($query) use ($request){
                    if($request->has('Id_Val') && !empty($request->get('Id_Val'))){
                        $query->where('Id_Val', $request->get('Id_Val'));
                    }
                })
                ->make(true);
        }
}*/
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $ver_dup= Beneficiarios::where('Curp',$request->Curp)->count();
        $Estatus_Curp='';
        if($ver_dup==0){
            $Estatus_Curp='ACEPTADO';
        }else{
            $Estatus_Curp='DUPLICADO Y RECHAZADO';
        }
        //$fileNameToStore='no_image.jpg';
        if($request->hasFile('Documentos')){//hay un archivo seleccionado
            $fileNameWithExt = $request->file('Documentos')->getClientOriginalName();
            $path = $request->file('Documentos')->storeAs('public/Doc_Ben/'.$request->Id_Val_Form.'/'.$request->Curp,$fileNameWithExt);

        }else{
            $fileNameWithExt='';
        }
        
        if($Estatus_Curp!='ACEPTADO' || $Estatus_Curp!='DUPLICADO Y RECHAZADO'){
            $ver_dup= Beneficiarios::where('Curp',$request->Curp)->count();
            if($ver_dup==0){
                $Estatus_Curp='ACEPTADO';
            }else{
                $Estatus_Curp='DUPLICADO Y RECHAZADO';
            }
        }
        
        $form_data = array(
            'Id_Val'        =>  $request->Id_Val_Form,
            'Id_Sol'        =>  $request->Id_Sol_Form,
            'Id_Pro'        =>  $request->Id_Pro,
            'Nom_Ben'        =>  $request->Nom_Ben,
            'Pat_Ben'        =>  $request->Pat_Ben,
            'Mat_Ben'        =>  $request->Mat_Ben,
            'Sexo'        =>  $request->Sexo,
            'Clave_El'        =>  $request->Clave_El,
            'Curp'        =>  $request->Curp,
            'Id_Loc'        =>  $request->Id_Loc1,
            'Id_Reg'        =>  $request->Id_Reg1,
            'Estatus'        =>  $Estatus_Curp,
            'Estatus2'        =>  1,
            'Id_Usuario'        =>  auth()->user()->id,
            'Documentos'        =>  $fileNameWithExt,
            'Comentario'        =>  $request->Comentario
        );
        Beneficiarios::insert($form_data);//primera llamar el modelo creado
        
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
            $ben_editar= Beneficiarios::findOrFail($id);
            return response()->json(['result'=>$ben_editar]);
        }
    }

    public function duplicados($id)
    {
        if(request()->ajax()){

            if($id==0){
                $ben_duplicados = Beneficiarios::where('Estatus','DUPLICADO Y RECHAZADO')->count();
           }else{
                $ben_duplicados = Beneficiarios::where('Estatus','DUPLICADO Y RECHAZADO')->where('Id_Val', $id)->count();
           }
           return response()->json(['result'=>$ben_duplicados]);
            
        }
    }

    public function pdf($id)
    {
        if(request()->ajax()){
            $ben_editar= Beneficiarios::findOrFail($id);
            return response()->json(['result'=>$ben_editar]);
        }
    }

    public function NumReg($id)
    {
        if(request()->ajax()){
            $ben_editar= Beneficiarios::where('Id_Val',$id)->count();
            
            return response()->json(['result'=>$ben_editar]);
        }
    }

    public function VerProyectos($id)
    {
        if(request()->ajax()){
            /*$tipos = Beneficiarios::select('Id_Pro')
                     ->count()
                     ->with('proyectos')
                     ->where('Id_Val', '=', $id)
                     ->groupBy('Id_Pro')
                     ->first();*/

             $tipos = Beneficiarios::select("Id_Pro")
                        ->selectRaw('COUNT(Id_Pro)')
                        ->with('proyectos')
                        ->where("Id_Val",$id)
                        ->groupBy('Id_Pro')
                        ->get();
            return response()->json(['result'=>$tipos]);
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
        //verificando si hizo cambio de curp y si hizo cambio de una vez moverlo a la carpeta de curp nuevo
        $id_val=$request->Id_Val_Form;
        $pdfviejo=$request->Archivo_Viejo;//nombre del archivo con valor de bd, para no editar en caso de no haber ninguna informacion
        $curpviejo=$request->Curp_Viejo;

        $curpnuevo=$request->Curp;
        //verificar primero si se cambio la curp
        if($curpviejo==$curpnuevo){

        }else{
            Storage::move('public/Doc_Ben/'.$id_val.'/'.$curpviejo.'/'.$pdfviejo, 'public/Doc_Ben/'.$id_val.'/'.$curpnuevo.'/'.$pdfviejo);
            Storage::deleteDirectory('public/Doc_Ben/'.$id_val.'/'.$curpviejo);
        }

//=================verificar si hizo cambio de archivo================
//====================================================================

        if($pdfviejo!=""){//hay archivo cuando registro
            if($request->hasFile('Documentos')){// si hay otro archivo seleccionado
                if(Storage::exists('public/Doc_Ben/'.$id_val.'/'.$curpnuevo.'/'.$pdfviejo)){
                     Storage::delete('public/Doc_Ben/'.$id_val.'/'.$curpnuevo.'/'.$pdfviejo);
                     $miarchivo = $request->file('Documentos')->getClientOriginalName();
                     $path = $request->file('Documentos')->storeAs('public/Doc_Ben/'.$id_val.'/'.$curpnuevo,$miarchivo);
                }
            }
            else{
                $miarchivo=$pdfviejo;
            }
        }else{//no guardo archivo al registrar
            if($request->hasFile('Documentos')){//no guardo archivo al registrar, pero ahora ya guardo
                $miarchivo = $request->file('Documentos')->getClientOriginalName();
                $path = $request->file('Documentos')->storeAs('public/Doc_Ben/'.$id_val.'/'.$curpnuevo,$miarchivo);
            }else{
                $miarchivo='';
            }
        }
//=============ahora se verificaran los campos para guardar actualizacion======
//=============================================================================
        $rules = array(
            'Nom_Ben'    =>  'required',
            'Pat_Ben'    =>  'required',
            'Mat_Ben'    =>  'required',
            'Clave_El'    =>  'required',
            'Curp'    =>  'required'

        );
        $error = Validator::make($request->all(), $rules);
        if($error->fails()){
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'Id_Val'        =>  $request->Id_Val_Form,
            'Id_Sol'        =>  $request->Id_Sol_Form,
            'Id_Pro'        =>  $request->Id_Pro,
            'Nom_Ben'        =>  $request->Nom_Ben,
            'Pat_Ben'        =>  $request->Pat_Ben,
            'Mat_Ben'        =>  $request->Mat_Ben,
            'Sexo'        =>  $request->Sexo,
            'Clave_El'        =>  $request->Clave_El,
            'Curp'        =>  $request->Curp,
            'Id_Loc'        =>  $request->Id_Loc1,
            'Id_Reg'        =>  $request->Id_Reg1,
            'Estatus'        =>  $request->Estatus,
            'Estatus2'        =>  1,
            'Id_Usuario'        =>  auth()->user()->id,
            'Documentos'        =>  $miarchivo,
            'Comentario'        =>  $request->Comentario
        );
        Beneficiarios::whereId($request->hidden_id)->update($form_data);
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
        $data = Beneficiarios::findOrFail($id);
        $id_val=$data->Id_Val;
        $curp=$data->Curp;
        $archivo=$data->Documentos;
        
        //dd('public/Doc_Ben/'.$id_val.'/'.$curp.'/'.$archivo);
       if(Storage::exists('public/Doc_Ben/'.$id_val.'/'.$curp.'/'.$archivo)){
            Storage::delete('public/Doc_Ben/'.$id_val.'/'.$curp.'/'.$archivo);

            Storage::deleteDirectory('public/Doc_Ben/'.$id_val.'/'.$curp);
        }/*else{
            dd('public/Doc_Sol/'.$datafolio.'/'.$dataarchivo);
        }*/
        $data->delete();

    }

}
