<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Productos;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.productos.index');
    }

    public function datatable(Request $request){
        if ($request->ajax()) {
                $productos = productos::select('id','Tipo','Descripcion','Existencia','Salida','Stock');

                return DataTables()
                ->eloquent($productos)
                ->addColumn('action_producto', function($productos){
                        $button = '<button type="button" name="edit" id="'.$productos->id.'" class="edit btn btn-success"><i class="fas fa-fw fa-edit "></i></button>';
                        $button .= '<button type="button" name="El_Ben" id="'.$productos->id.'" class="El_Ben btn btn-danger"><i class="fas fa-fw fa-trash"></i></button>';//eliminar
                        return $button;
                    })
                ->rawColumns(['action_producto'])
                ->toJson(); 
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function datos()
    {
        if(request()->ajax()){
            $productos= Productos::select('id','Descripcion')->get();
            //dd($productos);
            return response()->json(['result'=>$productos]);
        }
    }

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
        $form_data = array(
            'Tipo'        =>  $request->Tipo,
            'Descripcion'        =>  $request->Descripcion,
            'Existencia'        =>  $request->Existencia,
            'Salida'        =>  '0',
            'Stock'        =>  '0'
        );
        Productos::insert($form_data);//primera llamar el modelo creado
        
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
