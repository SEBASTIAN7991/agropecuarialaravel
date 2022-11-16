<?php

namespace App\Http\Controllers\Admin;
use App\Models\Admin\Proyectos;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\Datatables\DataTables;
use Validator;

class ProyectosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $mis_proy = Proyectos::select('id','Nom_Pro','Monto_Pro','created_at','updated_at')->get();
            return Datatables()->of($mis_proy)->addIndexColumn()
            ->addColumn('action_pro', function($mis_proy){
                    $button = '<button type="button" name="edit" id="'.$mis_proy->id.'" class="edit btn btn-primary"><i class="fas fa-fw fa-edit "></i></button>';
                    $button .= '<button type="button" name="el_pro" id="'.$mis_proy->id.'" proyecto="'.$mis_proy->Nom_Pro.'" class="el_pro btn btn-danger"><i class="fas fa-fw fa-trash"></i></button>';
                    return $button;
                })
            ->rawColumns(['action_pro'])
            ->toJson();
        }
        return view('admin.proyectos.index');
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
        $validAreas=[
            'Nom_Pro'=>'required|string|max:70',
            'Monto_Pro'=>'required|string|max:70'
        ];
        $rules = array(
            'Nom_Pro'    =>  'required',
            'Monto_Pro'    =>  'required'
        );
        $error = Validator::make($request->all(), $rules);
        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }
        $form_data = array(
            'Nom_Pro'        =>  $request->Nom_Pro,
            'Monto_Pro'        =>  $request->Monto_Pro,
            'Estatus'        =>  $request->Estatus
        );
        Proyectos::insert($form_data);//primera llamar el modelo creado
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
            $data= Proyectos::findOrFail($id);
            return response()->json(['result'=>$data]);
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
        $rules = array(
            'Nom_Pro'         =>  'required',
            'Monto_Pro'         =>  'required'
        );
 
        $error = Validator::make($request->all(), $rules);
 
        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }
 
        $form_data = array(
            'Nom_Pro'     =>  $request->Nom_Pro,
            'Monto_Pro'     =>  $request->Monto_Pro,
            'Estatus'        =>  $request->Estatus
        );
 
        Proyectos::whereId($request->hidden_id)->update($form_data);
 
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
        $data = Proyectos::findOrFail($id);
        $data->delete();
    }
}
