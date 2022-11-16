<?php
namespace App\Http\Controllers\Admin;

use App\Models\Admin\areas;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\Datatables\DataTables;
use Validator;

class AreasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = areas::select('id','Area','Responsable','created_at','updated_at')->get();
            return Datatables()->of($data)->addIndexColumn()
            ->addColumn('action', function($data){
                    $button = '<button type="button" name="edit" id="'.$data->id.'" class="edit btn btn-primary">Edit</button>';
                    $button .= '<button type="button" name="delete" id="'.$data->id.'" area="'.$data->Area.'" class="delete btn btn-danger">Delete</button>';
                    return $button;
                })
            ->rawColumns(['action'])
            ->toJson();

            /*$data = areas::select('id','Area','Responsable','created_at','updated_at')->get();
            return Datatables::of($data)->addIndexColumn()
                ->addColumn('action', function($data){
                    $button = '<button type="button" name="edit" id="'.$data->id.'" class="edit btn btn-primary">Edit</button>';
                    $button .= '<button type="button" name="delete" id="'.$data->id.'" area="'.$data->Area.'" class="delete btn btn-danger">Delete</button>';
                    return $button;
                })
                ->make(true);*/
        }
 
        return view('admin.areas.index');
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
            'Area'=>'required|string|max:50',
            'Responsable'=>'required|string',
        ];
        
        $rules = array(
            'Area'    =>  'required',
            'Responsable'     =>  'required'
        );
 
        $error = Validator::make($request->all(), $rules);
 
        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }
 
 
        $form_data = array(
            'Area'        =>  $request->Area,
            'Responsable'         =>  $request->Responsable
        );
        areas::insert($form_data);//primera llamar el modelo creado

        return response()->json(['success' => 'Data Added successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin\areas  $areas
     * @return \Illuminate\Http\Response
     */
    public function show(areas $areas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin\areas  $areas
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(request()->ajax())
        {
            $data = areas::findOrFail($id);
            return response()->json(['result' => $data]);
        }   
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin\areas  $areas
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $rules = array(
            'Area'        =>  'required',
            'Responsable'         =>  'required'
        );
 
        $error = Validator::make($request->all(), $rules);
 
        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }
 
        $form_data = array(
            'Area'    =>  $request->Area,
            'Responsable'     =>  $request->Responsable
        );
 
        areas::whereId($request->hidden_id)->update($form_data);
 
        return response()->json(['success' => 'Data is successfully updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin\areas  $areas
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = areas::findOrFail($id);
        $data->delete();
    }
}
