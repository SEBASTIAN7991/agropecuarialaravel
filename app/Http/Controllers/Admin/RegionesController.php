<?php

namespace App\Http\Controllers\Admin;
use App\Models\Admin\Regiones;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\Datatables\DataTables;
use Validator;

class RegionesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $mis_reg = Regiones::select('id','Nom_Reg','created_at','updated_at')->get();
            return Datatables()->of($mis_reg)->addIndexColumn()
            ->addColumn('action_reg', function($mis_reg){
                    $button = '<button type="button" name="edit" id="'.$mis_reg->id.'" class="edit btn btn-primary"><i class="fas fa-fw fa-edit "></i></button>';
                    $button .= '<button type="button" name="el_reg" id="'.$mis_reg->id.'" region="'.$mis_reg->Nom_Reg.'" class="el_reg btn btn-danger"><i class="fas fa-fw fa-trash"></i></button>';
                    return $button;
                })
            ->rawColumns(['action_reg'])
            ->toJson();
        }
        return view('admin.regiones.index');
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
            'Nom_Reg'=>'required|string|max:60'
        ];
        $rules = array(
            'Nom_Reg'    =>  'required'
        );
        $error = Validator::make($request->all(), $rules);
        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }
        $form_data = array(
            'Nom_Reg'        =>  $request->Nom_Reg
        );
        Regiones::insert($form_data);//primera llamar el modelo creado
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
            $data= Regiones::findOrFail($id);
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
            'Nom_Reg'         =>  'required'
        );
 
        $error = Validator::make($request->all(), $rules);
 
        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }
 
        $form_data = array(
            'Nom_Reg'     =>  $request->Nom_Reg
        );
 
        Regiones::whereId($request->hidden_id)->update($form_data);
 
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
        $data = Regiones::findOrFail($id);
        $data->delete();
    }
}
