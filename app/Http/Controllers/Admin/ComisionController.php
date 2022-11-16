<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\Datatables\DataTables;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Models\Admin\Comisiones;
use App\Models\Admin\Personas;
use Validator;
use Storage;
use PDF;
use Carbon\Carbon;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Font;
use Exception;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use App\Exports\Admin\ComisionesExport;


class ComisionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $mis_comisiones = Comisiones::select('id','Fecha_Emision','Id_Comisionado','Loc_Destino','Fecha_Comision')
            ->with('personas');

            return DataTables()
            ->eloquent($mis_comisiones)
            ->addColumn('action_Com', function($mis_comisiones){
                $button = '<button type="button" name="edit" id="'.$mis_comisiones->id.'" class="edit btn btn-success"><i class="fas fa-fw fa-edit "></i></button>';
                $button .= '<button type="button" name="elicom" id="'.$mis_comisiones->id.'" nombre="'.$mis_comisiones->personas->Nombre.'" fecha="'.$mis_comisiones->Fecha_Comision.'" destino="'.$mis_comisiones->Loc_Destino.'" class="elicom btn btn-danger"><i class="fas fa-fw fa-trash"></i></button>';//eliminar
                if(Storage::exists('public/Doc_Com/'.$mis_comisiones->Fecha_Emision.'/ORD_'.$mis_comisiones->personas->Nombre.'.docx')){
                    $button .= '<button type="button" name="ExpExis" class="ExpExis btn btn-dark"><i class="fas fa-file-word"></i></button>';
                }else{
                    $button .= '<button type="button" name="GenExp" id="'.$mis_comisiones->id.'" class="GenExp btn btn-dark"><i class="fas fa-file"></i></button>';
                }
                return $button;
                })
            ->rawColumns(['action_Com'])
            ->toJson();
        }
        $Personas=Personas::select('id','Nombre')->get();
        return view('admin.comisiones.index',compact('Personas'));
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
        $mytime = new Carbon();
        $formato=$mytime->toDateString();
        return Excel::store(new ComisionesExport(), $formato.'_Viaticos.xlsx','respaldos');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $Fecha_Em=$request->Fecha_Emision;
        $Loc_Destino=$request->Loc_Destino;
        $Activi=$request->Actividad;
        $Fecha_Com=$request->Fecha_Comision;
        $Actividad=$request->Actividad;
        $Comentario=$request->Comentario;
        $Estatus=$request->Estatus;
        $Dias=$request->Dias;
        if($request->Placas=='DA1198A'){
            $Tipo_Trans='OFICIAL';
            $Placas='DA1198A';
            $Num_Eco='CAC801';
            $Programa='GASTO CORRIENTE';
        }
        if($request->Placas=='DA1189A'){
            $Tipo_Trans='OFICIAL';
            $Placas='DA1189A';
            $Num_Eco='CI1102';
            $Programa='GASTO CORRIENTE';
        }
        if($request->Placas=='---------------'){
            $Tipo_Trans='PARTICULAR';
            $Placas='---------------';
            $Num_Eco='---------------';
            $Programa='---------------';
        }

        if ( $request->has('Id_Comisionado') ) {
            foreach ( $request->get('Id_Comisionado') as $peso ) {
                Comisiones::insert([
                    'Fecha_Emision' =>$Fecha_Em,
                    'Id_Comisionado' => $peso,
                    'Loc_Destino' =>$Loc_Destino,
                    'Fecha_Comision'=>$Fecha_Com,
                    'Actividad' =>$Activi,
                    'Tipo_Trans'=>$Tipo_Trans,
                    'Placas' =>$Placas,
                    'Num_Eco' =>$Num_Eco,
                    'Programa' =>$Programa,
                    'Comentario'=>$Comentario,
                    'Estatus'=>$Estatus,
                    'Dias'=>$Dias
                ]);
            }
            return response()->json(['success' => 'Guardado']);
        }
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

    public function word(Request $request, $id){
        $comision= Comisiones::select('id','Fecha_Emision','Id_Comisionado','Loc_Destino','Fecha_Comision','Actividad','Tipo_Trans','Placas','Num_Eco','Programa','Comentario','Estatus','Dias')
            ->with('personas')
            ->findOrFail($id);
        $NOMBRE=$comision->personas->Nombre;
        $CARGO=$comision->personas->Cargo;
        $DESTINO=$comision->Loc_Destino;
        $FECHASCOMISION=$comision->Fecha_Comision;
        $ACTIVIDAD=$comision->Actividad;
        $TRANSPORTE=$comision->Tipo_Trans;
        $PLACAS=$comision->Placas;
        $ECONOMICO=$comision->Num_Eco;
        $PROGRAMA=$comision->Programa;
        //comienza el parseo de fecha en español
        $FECHAEM=$comision->Fecha_Emision;
        $FECHAEM2 = Carbon::parse($FECHAEM);
        //$date = $fecha->locale('es')->translatedFormat('l d \d\e F \d\e\l Y'); para obtener dia fecha mes y año en español
        $FECHACOMP = $FECHAEM2->locale('es')->translatedFormat('d \d\e F \d\e\l Y');
        $FECHAMAYUS = strtoupper($FECHACOMP);
        //LLENADO DE ORDEN DE COMISION
        Storage::makeDirectory('public/Doc_Com/'.$FECHAEM);
        $DOC_ORDEN = new \PhpOffice\PhpWord\TemplateProcessor('Plantillas/ORDEN.docx');
        $DOC_ORDEN->setValue('EMISION', $FECHAMAYUS);
        $DOC_ORDEN->setValue('NOMBRE', $NOMBRE);
        $DOC_ORDEN->setValue('CARGO', $CARGO);
        $DOC_ORDEN->setValue('DESTINO', $DESTINO);
        $DOC_ORDEN->setValue('FECHAS', $FECHASCOMISION);
        $DOC_ORDEN->setValue('ACTIVIDAD', $ACTIVIDAD);
        $DOC_ORDEN->setValue('TRANSPORTE', $TRANSPORTE);
        $DOC_ORDEN->setValue('PLACAS', $PLACAS);
        $DOC_ORDEN->setValue('ECONOMICO', $ECONOMICO);
        $DOC_ORDEN->setValue('PROGRAMA', $PROGRAMA);
        $DOC_ORDEN->saveAs('storage/Doc_Com/'.$FECHAEM.'/ORD_'.$NOMBRE.'.docx');
        //LLENADO DE OFICIO DE COMISION
        $DOC_OFICIO = new \PhpOffice\PhpWord\TemplateProcessor('Plantillas/OFICIO.docx');
        $DOC_OFICIO->setValue('EMISION', $FECHAMAYUS);
        $DOC_OFICIO->setValue('NOMBRE', $NOMBRE);
        $DOC_OFICIO->setValue('CARGO', $CARGO);
        $DOC_OFICIO->setValue('DESTINO', $DESTINO);
        $DOC_OFICIO->setValue('FECHAS', $FECHASCOMISION);
        $DOC_OFICIO->setValue('ACTIVIDAD', $ACTIVIDAD);
        $DOC_OFICIO->saveAs('storage/Doc_Com/'.$FECHAEM.'/OFI_'.$NOMBRE.'.docx');
        //LLENADO DE COMISION POR COMUNIDAD
        for ($i=1; $i < $comision->Dias+1; $i++) {
                $DOC_OFICIO = new \PhpOffice\PhpWord\TemplateProcessor('Plantillas/COMISION.docx');
                $DOC_OFICIO->setValue('NOMBRE', $NOMBRE);
                $DOC_OFICIO->saveAs('storage/Doc_Com/'.$FECHAEM.'/COM_'.$NOMBRE.'_'.$i.'.docx');    
             
            
        }
        

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
            $comision= Comisiones::select('id','Fecha_Emision','Id_Comisionado','Loc_Destino','Fecha_Comision','Actividad','Placas','Comentario','Estatus','Dias')
            ->findOrFail($id);
            return response()->json(['result'=>$comision]);
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
        $Id_Comisionado='';
        if ( $request->has('Id_Comisionado') ) {
            foreach ( $request->get('Id_Comisionado') as $peso ) {
                $Id_Comisionado=$peso;
            }
        }

        $Fecha_Em=$request->Fecha_Emision;
        $Loc_Destino=$request->Loc_Destino;
        $Activi=$request->Actividad;
        $Fecha_Com=$request->Fecha_Comision;
        $Actividad=$request->Actividad;
        $Comentario=$request->Comentario;
        $Estatus=$request->Estatus;
        $Dias=$request->Dias;
        
        if($request->Placas=='DA1198A'){
            $Tipo_Trans='OFICIAL';
            $Placas='DA1198A';
            $Num_Eco='CAC801';
            $Programa='GASTO CORRIENTE';
        }
        if($request->Placas=='DA1189A'){
            $Tipo_Trans='OFICIAL';
            $Placas='DA1189A';
            $Num_Eco='CI1102';
            $Programa='GASTO CORRIENTE';
        }
        if($request->Placas=='---------------'){
            $Tipo_Trans='PARTICULAR';
            $Placas='---------------';
            $Num_Eco='---------------';
            $Programa='---------------';
        }
        $form_data = array(
            'Fecha_Emision' => $Fecha_Em,
            'Id_Comisionado' => $Id_Comisionado,
            'Loc_Destino' => $Loc_Destino,
            'Fecha_Comision' => $Fecha_Com,
            'Actividad' => $Actividad,
            'Tipo_Trans' => $Tipo_Trans,
            'Placas' => $Placas,
            'Num_Eco' => $Num_Eco,
            'Programa' => $Programa,
            'Comentario' => $Comentario,
            'Estatus'=>$Estatus,
            'Dias'=>$Dias
        );
        Comisiones::whereId($request->hidden_id)->update($form_data);
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
        $data = Comisiones::findOrFail($id);
        $data->delete();
    }
}
