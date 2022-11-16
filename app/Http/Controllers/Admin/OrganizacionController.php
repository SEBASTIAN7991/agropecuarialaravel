<?php

namespace App\Http\Controllers\Admin;
use App\Models\Admin\Organizaciones;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\Datatables\DataTables;
use Validator;


class OrganizacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $mis_org = Organizaciones::select('id','Representante','Nom_Org','created_at','updated_at')->get();
            return Datatables()->of($mis_org)->addIndexColumn()
            ->addColumn('action_org', function($mis_org){
                    $button = '<button type="button" name="edit" id="'.$mis_org->id.'" class="edit btn btn-primary"><i class="fas fa-fw fa-edit "></i></button>';
                    $button .= '<button type="button" name="el_org" id="'.$mis_org->id.'" ore="'.$mis_org->Nom_Org.'" class="el_org btn btn-danger"><i class="fas fa-fw fa-trash"></i></button>';
                    return $button;
                })
            ->rawColumns(['action_org'])
            ->toJson();
        }
        return view('admin.organizaciones.index');
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
            'Representante'=>'required|string|max:70',
            'Nom_Org'=>'required|string|max:120'
        ];
        $rules = array(
            'Representante'    =>  'required',
            'Nom_Org'    =>  'required'
        );
        $error = Validator::make($request->all(), $rules);
        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }
        $form_data = array(
            'Representante'        =>  $request->Representante,
            'Nom_Org'        =>  $request->Nom_Org
        );
        Organizaciones::insert($form_data);//primera llamar el modelo creado
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
            $data = Organizaciones::findOrFail($id);
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
            'Representante'         =>  'required',
            'Nom_Org'         =>  'required'
        );
 
        $error = Validator::make($request->all(), $rules);
 
        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }
 
        $form_data = array(
            'Representante'     =>  $request->Representante,
            'Nom_Org'     =>  $request->Nom_Org
        );
 
        Organizaciones::whereId($request->hidden_id)->update($form_data);
 
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
        $data = Organizaciones::findOrFail($id);
        $data->delete();
    }
}
