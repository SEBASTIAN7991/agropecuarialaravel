<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Localidades;
use App\Models\Admin\Regiones;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\Datatables\DataTables;
use Validator;

class LocalidadesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $mis_loc = Localidades::select('id','Nom_Loc','Id_Reg','created_at','updated_at')->with('regiones');
            //$mis_loc = Localidades::select('id','Nom_Loc','Id_Reg','created_at','updated_at')->get();
            return DataTables()
            ->eloquent($mis_loc)
            ->addColumn('action_loc', function($mis_loc){
                    $button = '<button type="button" name="edit" id="'.$mis_loc->id.'" class="edit btn btn-primary"><i class="fas fa-fw fa-edit "></i></button>';
                    $button .= '<button type="button" name="el_loc" id="'.$mis_loc->id.'" localidad="'.$mis_loc->Nom_Loc.'" class="el_loc btn btn-danger"><i class="fas fa-fw fa-trash"></i></button>';
                    return $button;
                })
            ->rawColumns(['action_loc'])
            ->toJson();
        }

//        return view('admin.localidades.index');
       $regiones=Regiones::all();
        return view('admin.localidades.index',compact('regiones'));
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
            'Nom_Loc'    =>  'required',
            'Id_Reg'    =>  'required'
        );
        $error = Validator::make($request->all(), $rules);
        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }
        $form_data = array(
            'Nom_Loc'        =>  $request->Nom_Loc,
            'Id_Reg'        =>  $request->Id_Reg
        );
        Localidades::insert($form_data);//primera llamar el modelo creado
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
            $data= Localidades::findOrFail($id);
            return response()->json(['result'=>$data]);
        }
    }

    public function datos($id)
    {

        if(request()->ajax()){
            $proyectos= Localidades::select('id','Nom_Loc')
            ->where('Nom_Loc', $id)
            ->firstOrFail();
            return response()->json(['result'=>$proyectos]);
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
        $validAreas=[
            'Nom_Loc'=>'required|string|max:60',
            'Id_Reg'=>'required'
        ];
        $rules = array(
            'Nom_Loc'    =>  'required',
            'Id_Reg'    =>  'required'
        );
        $error = Validator::make($request->all(), $rules);
        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }
 
        $form_data = array(
            'Nom_Loc'        =>  $request->Nom_Loc,
            'Id_Reg'        =>  $request->Id_Reg
        );
 
        Localidades::whereId($request->hidden_id)->update($form_data);
 
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
        $data = Localidades::findOrFail($id);
        $data->delete();
    }
}
