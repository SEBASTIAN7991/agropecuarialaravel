<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Inventarios;


class InventarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $Recepcion=$request->Fecha_Recepcion;
        $productos=$request->NumLineas;
        $Descripcion=$request->DescripcionPro;
        $Existencia=$request->ExistenciaPro;

        $form_data = array(
                    'Fecha_Recepcion'        =>  $Recepcion,
                    'Id_Producto'        =>  $Descripcion,
                    'Cantidad'        =>  $Existencia
                );

        
        if($productos>1){
            for ($i=1; $i < $productos; $i++) {
                $hola=$request->Descripcion+'2';
                dd($hola);
               /*Inventarios::insert([
                    'Fecha_Recepcion' =>$request->Fecha_Recepcion,
                    'Id_Producto' => $request->Descripcion.''.$i,
                    'Cantidad' =>$request->Existencia.''.$i
                ]);*/  
            }
        }else{
            Inventarios::insert($form_data);
        }
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
