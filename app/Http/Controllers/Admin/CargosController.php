<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Cargos;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\Datatables\DataTables;
use Validator;

class CargosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $mis_cargos = Cargos::select('id','Nombre_Cargo','created_at','updated_at')->get();
            return Datatables()->of($mis_cargos)->addIndexColumn()
            ->addColumn('action_cargo', function($mis_cargos){
                    $button = '<button type="button" name="edit" id="'.$mis_cargos->id.'" class="edit btn btn-primary"><i class="fas fa-fw fa-edit "></i></button>';
                    $button .= '<button type="button" name="el_cargo" id="'.$mis_cargos->id.'" cargo="'.$mis_cargos->Nombre_Cargo.'" class="el_cargo btn btn-danger"><i class="fas fa-fw fa-trash"></i></button>';
                    return $button;
                })
            ->rawColumns(['action_cargo'])
            ->toJson();
        }
        return view('admin.cargos.index');
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
            'Nombre_Cargo'=>'required|string|max:70'
        ];
        
        $rules = array(
            'Nombre_Cargo'    =>  'required'
        );
 
        $error = Validator::make($request->all(), $rules);
 
        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }
 
 
        $form_data = array(
            'Nombre_Cargo'        =>  $request->Nombre_Cargo
        );
        Cargos::insert($form_data);//primera llamar el modelo creado

        return response()->json(['success' => 'Data Added successfully.']);
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
        if(request()->ajax())
        {
            $data = Cargos::findOrFail($id);
            return response()->json(['result' => $data]);
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
            'Nombre_Cargo'         =>  'required'
        );
 
        $error = Validator::make($request->all(), $rules);
 
        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }
 
        $form_data = array(
            'Nombre_Cargo'     =>  $request->Nombre_Cargo
        );
 
        Cargos::whereId($request->hidden_id)->update($form_data);
 
        return response()->json(['success' => 'Data is successfully updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Cargos::findOrFail($id);
        $data->delete();
    }
}
