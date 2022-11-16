<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Solicitudes;
use App\Models\Admin\Organizaciones;
use App\Models\Admin\Localidades;
use App\Exports\Admin\ReporteSolicitudes;
use App\Models\Admin\Cargos;
use App\Models\Admin\Regiones;
use App\Models\Admin\Proyectos;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Admin\SolicitudesExcel;
use Yajra\Datatables\DataTables;
use Validator;
use Storage;


class SolicitudesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $mis_Sol = Solicitudes::select('id','Folio','Id_Org','Fecha_Sol','Id_Loc','Id_Org','Id_Pro','Subrepresentante','Ubicacion_Archivo','updated_at')
            ->with('localidades')
            ->with('proyectos')
            ->with('organizaciones');

            return DataTables()
            ->eloquent($mis_Sol)
            ->addColumn('action_Sol', function($mis_Sol){
                    $button = '<button type="button" name="edit" id="'.$mis_Sol->id.'" class="edit btn btn-success"><i class="fas fa-fw fa-edit "></i></button>';
                    $button .= '<button type="button" name="elisol" id="'.$mis_Sol->id.'" solicitud="'.$mis_Sol->Subrepresentante.'" class="elisol btn btn-danger"><i class="fas fa-fw fa-trash"></i></button>';//eliminar
                    if(empty($mis_Sol->Ubicacion_Archivo)){
                        $button .= '<button type="button" name="sin_pdf" class="sin_pdf btn btn-warning"><i class="fas fa-fw fa-file"></i></button>';
                    }
                    else{
                    $button .= '<button type="button" name="ver_pdf_sol" id="'.$mis_Sol->id.'" folio="'.$mis_Sol->Folio.'" archivo="'.$mis_Sol->Ubicacion_Archivo.'" class="ver_pdf_sol btn btn-dark"><i class="fas fa-fw fa-file-pdf"></i></button>';
                    }
                    return $button;
                })
            ->rawColumns(['action_Sol'])
            ->toJson();

        }
        $organizaciones=Organizaciones::select('id','Nom_Org')->get();
        $cargos=Cargos::select('id','Nombre_Cargo')->get();
        $localidades=Localidades::select('id','Nom_Loc','Id_Reg')->get();
        $proyectos=Proyectos::select('id','Nom_Pro')->get();
        return view('admin.solicitudes.index',compact('organizaciones','cargos','localidades','proyectos'));
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

    public function excel(Request $request){
        return (new ReporteSolicitudes())->download('solicitudes.xlsx');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function mguardar(Request $request){

    }

    public function store(Request $request)
    {
        $ultimoid = Solicitudes::max('id');
        if($ultimoid==null){
            $ultimoid=0;
        }if($ultimoid>=1){
            $ultimoid=$ultimoid;
        }

        $fechaFormato = date('d-m-Y');
        $fechafinal = str_replace("-", "", $fechaFormato);
        $foliofinal='AGR_'.$fechafinal.'_'.$ultimoid+1;


        $rules = array(
            'Id_Org'    =>  'required',
            'Id_Cargo'    =>  'required',
            'Fecha_Sol'    =>  'required',
            'Id_Loc'    =>  'required',
            'Id_Reg'    =>  'required',
            'Id_Pro'    =>  'required',
            'Subrepresentante'    =>  'required',
            'Telefono'    =>  'required',
            'Cant_Sol'    =>  'required',
            'Ben_H'    =>  'required',
            'Ben_M'    =>  'required',
            'Ubicacion_Archivo'    =>  'required',
            'Convenio'    =>  'required',
            'Tipo_Convenio'    =>  'required'

        );
        $error = Validator::make($request->all(), $rules);
        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

              //$fileNameToStore='no_image.jpg';
            if($request->hasFile('Ubicacion_Archivo')){//hay un archivo seleccionado
                $fileNameWithExt = $request->file('Ubicacion_Archivo')->getClientOriginalName();
                $path = $request->file('Ubicacion_Archivo')->storeAs('public/Doc_Sol/'.$foliofinal,$fileNameWithExt);

            }else{
                $fileNameWithExt='';
            }

        
        $form_data = array(
            'Folio' => $foliofinal,
            'Id_Org'        =>  $request->Id_Org,
            'Id_Cargo'        =>  $request->Id_Cargo,
            'Fecha_Sol'        =>  $request->Fecha_Sol,
            'Id_Loc'        =>  $request->Id_Loc,
            'Id_Reg'        =>  $request->Id_Reg,
            'Id_Pro'        =>  $request->Id_Pro,
            'Subrepresentante'        =>  $request->Subrepresentante,
            'Telefono'        =>  $request->Telefono,
            'Cant_Sol'        =>  $request->Cant_Sol,
            'Ben_H'        =>  $request->Ben_H,
            'Ben_M'        =>  $request->Ben_M,
            'Ubicacion_Archivo'        =>  $fileNameWithExt,
            'Convenio'        =>  $request->Convenio,
            'Tipo_Convenio'        =>  $request->Tipo_Convenio,
            'Validado' => 0,
            'Comentario'        =>  $request->Comentario
        );
        Solicitudes::insert($form_data);//primera llamar el modelo creado
        
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
            $sol_editar= Solicitudes::findOrFail($id);
            return response()->json(['result'=>$sol_editar]);
        }
    }


    public function VerRegion($id)
    {
        if(request()->ajax()){
            $reg_sel= Localidades::select('Id_Reg')
            ->with('regiones')
            ->findOrFail($id);
            return response()->json(['result'=>$reg_sel]);
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
        $miarchivo=$request->ArchivoExis;//nombre del archivo con valor de bd, para no editar en caso de no haber ninguna informacion
        $datafolio=$request->FolioExis;
        if($request->ArchivoExis!=""){//hay archivo
            if($request->hasFile('Ubicacion_Archivo')){// si hay otro archivo seleccionado
                //dd('el usuario guardo un archivo al registrar, y ahora lo cambia');
                $datafolio=$request->FolioExis;//nombre del folio
                $dataarchivo=$request->ArchivoExis;//nombre del archivo
                if(Storage::exists('public/Doc_Sol/'.$datafolio.'/'.$dataarchivo)){
                    Storage::delete('public/Doc_Sol/'.$datafolio.'/'.$dataarchivo);
                     $miarchivo = $request->file('Ubicacion_Archivo')->getClientOriginalName();
                     $path = $request->file('Ubicacion_Archivo')->storeAs('public/Doc_Sol/'.$datafolio,$miarchivo);
                }
            }
            else{
                //dd('el usuario guardo al registrar un archivo, pero no lo cambio');
            }
        }else{
            if($request->hasFile('Ubicacion_Archivo')){//no guardo archivo al registrar, pero ahora ya guardo
                //dd('el usuario no guardo archivo al registrar la solicitud pero ahora ya lo esta guardando');
                $miarchivo = $request->file('Ubicacion_Archivo')->getClientOriginalName();
                $path = $request->file('Ubicacion_Archivo')->storeAs('public/Doc_Sol/'.$datafolio,$miarchivo);

            }else{

                $miarchivo = '';
            }
        }


        $rules = array(
            'Id_Org'    =>  'required',
            'Id_Cargo'    =>  'required',
            'Fecha_Sol'    =>  'required',
            'Id_Loc'    =>  'required',
            'Id_Reg'    =>  'required',
            'Id_Pro'    =>  'required',
            'Subrepresentante'    =>  'required',
            'Telefono'    =>  'required',
            'Cant_Sol'    =>  'required',
            'Ben_H'    =>  'required',
            'Ben_M'    =>  'required',
            'Convenio'    =>  'required',
            'Tipo_Convenio'    =>  'required'

        );
        $error = Validator::make($request->all(), $rules);
        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }
        
        $form_data = array(
            'Folio' => $datafolio,
            'Id_Org'        =>  $request->Id_Org,
            'Id_Cargo'        =>  $request->Id_Cargo,
            'Fecha_Sol'        =>  $request->Fecha_Sol,
            'Id_Loc'        =>  $request->Id_Loc,
            'Id_Reg'        =>  $request->Id_Reg,
            'Id_Pro'        =>  $request->Id_Pro,
            'Subrepresentante'        =>  $request->Subrepresentante,
            'Telefono'        =>  $request->Telefono,
            'Cant_Sol'        =>  $request->Cant_Sol,
            'Ben_H'        =>  $request->Ben_H,
            'Ben_M'        =>  $request->Ben_M,
            'Ubicacion_Archivo'        =>  $miarchivo,
            'Convenio'        =>  $request->Convenio,
            'Tipo_Convenio'        =>  $request->Tipo_Convenio,
            'Validado' =>0,
            'Comentario'        =>  $request->Comentario
        );
        Solicitudes::whereId($request->hidden_id)->update($form_data);
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
        $data = Solicitudes::findOrFail($id);
        $datafolio=$data->Folio;
        $dataarchivo=$data->Ubicacion_Archivo;
        
       if(Storage::exists('public/Doc_Sol/'.$datafolio.'/'.$dataarchivo)){
            Storage::delete('public/Doc_Sol/'.$datafolio.'/'.$dataarchivo);
        }/*else{
            dd('public/Doc_Sol/'.$datafolio.'/'.$dataarchivo);
        }*/
        $data->delete();
    }
}
