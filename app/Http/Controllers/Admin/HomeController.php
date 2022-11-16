<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin\Beneficiarios;


class HomeController extends Controller
{

    public function index(Request $request){
        $id_us=auth()->user()->id;
        if ($request->ajax()) {
            $mis_bene = Beneficiarios::select('created_at')
            ->selectRaw('COUNT(Curp)')
            ->where('Id_Usuario',$id_us)
            ->groupBy('created_at')
            ->get();

            return Datatables()->of($mis_bene)
            ->toJson();
        }

        return view('admin.index');
    }
}
