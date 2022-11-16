<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AreasController;
use App\Http\Controllers\Admin\CargosController;
use App\Http\Controllers\Admin\OrganizacionController;
use App\Http\Controllers\Admin\RegionesController;
use App\Http\Controllers\Admin\ProyectosController;
use App\Http\Controllers\Admin\LocalidadesController;
use App\Http\Controllers\Admin\SolicitudesController;
use App\Http\Controllers\Admin\ValidacionesController;
use App\Http\Controllers\Admin\BeneficiariosController;
use App\Http\Controllers\Admin\FierrosController;
use App\Http\Controllers\Admin\VisitasController;
use App\Http\Controllers\Admin\OficiosController;
use App\Http\Controllers\Admin\ComisionController;
use App\Http\Controllers\Admin\ProductoController;
use App\Http\Controllers\Admin\InventarioController;

Route::get('', [HomeController::class, 'index'])->name('admin.index');


Route::resource('users',UserController::class)->names('admin.users');

//oute::resource('/areas',AreasController::class)->names('admin.areas');
Route::get('areas', [AreasController::class, 'index'])->middleware('can:admin.areas.index')->name('areas.index');
Route::post('areas/store', [AreasController::class, 'store'])->name('areas.store');
Route::get('areas/edit/{id}/', [AreasController::class, 'edit']);
Route::post('areas/update', [AreasController::class, 'update'])->name('areas.update');
Route::get('areas/destroy/{id}/', [AreasController::class, 'destroy']);

Route::get('cargos', [CargosController::class, 'index'])->middleware('can:admin.cargos.index')->name('cargos.index');
Route::post('cargos/store', [CargosController::class, 'store'])->name('cargos.store');
Route::get('cargos/edit/{id}/', [CargosController::class, 'edit']);
Route::post('cargos/update', [CargosController::class, 'update'])->name('cargos.update');
Route::get('cargos/destroy/{id}/', [CargosController::class, 'destroy']);

Route::get('organizaciones', [OrganizacionController::class, 'index'])->middleware('can:admin.organizaciones.index')->name('organizaciones.index');
Route::post('organizaciones/store', [OrganizacionController::class, 'store'])->name('organizaciones.store');
Route::get('organizaciones/edit/{id}/', [OrganizacionController::class, 'edit']);
Route::post('organizaciones/update', [OrganizacionController::class, 'update'])->name('organizaciones.update');
Route::get('organizaciones/destroy/{id}/', [OrganizacionController::class, 'destroy']);

Route::get('regiones', [RegionesController::class, 'index'])->middleware('can:admin.regiones.index')->name('regiones.index');
Route::post('regiones/store', [RegionesController::class, 'store'])->name('regiones.store');
Route::get('regiones/edit/{id}/', [RegionesController::class, 'edit']);
Route::post('regiones/update', [RegionesController::class, 'update'])->name('regiones.update');
Route::get('regiones/destroy/{id}/', [RegionesController::class, 'destroy']);

Route::get('proyectos', [ProyectosController::class, 'index'])->middleware('can:admin.proyectos.index')->name('proyectos.index');
Route::post('proyectos/store', [ProyectosController::class, 'store'])->name('proyectos.store');
Route::get('proyectos/edit/{id}/', [ProyectosController::class, 'edit']);
Route::post('proyectos/update', [ProyectosController::class, 'update'])->name('proyectos.update');
Route::get('proyectos/destroy/{id}/', [ProyectosController::class, 'destroy']);

Route::get('localidades', [LocalidadesController::class, 'index'])->middleware('can:admin.localidades.index')->name('localidades.index');
Route::post('localidades/store', [LocalidadesController::class, 'store'])->name('localidades.store');
Route::get('localidades/edit/{id}/', [LocalidadesController::class, 'edit']);
Route::get('localidades/datos/{id}/', [LocalidadesController::class, 'datos']);
Route::post('localidades/update', [LocalidadesController::class, 'update'])->name('localidades.update');
Route::get('localidades/destroy/{id}/', [LocalidadesController::class, 'destroy']);

Route::get('solicitudes', [SolicitudesController::class, 'index'])->middleware('can:admin.solicitudes.index')->name('solicitudes.index');
Route::post('solicitudes/store', [SolicitudesController::class, 'store'])->name('solicitudes.store');
Route::get('solicitudes/excel/', [SolicitudesController::class, 'excel'])->name('solicitudes.excel');
Route::get('solicitudes/edit/{id}/', [SolicitudesController::class, 'edit']);
Route::get('solicitudes/VerRegion/{id}/', [SolicitudesController::class, 'VerRegion']);
Route::post('solicitudes/update', [SolicitudesController::class, 'update'])->name('solicitudes.update');
Route::get('solicitudes/destroy/{id}/', [SolicitudesController::class, 'destroy']);


Route::get('validaciones', [ValidacionesController::class, 'index'])->middleware('can:admin.validaciones.index')->name('validaciones.index');
Route::post('validaciones/store', [ValidacionesController::class, 'store'])->name('validaciones.store');
Route::get('validaciones/edit/{id}/', [ValidacionesController::class, 'edit']);
Route::get('validaciones/excel/', [ValidacionesController::class, 'excel'])->name('validaciones.excel');
Route::get('validaciones/VerDatos/{id}/', [ValidacionesController::class, 'VerDatos']);
Route::get('validaciones/mod_ben_val/{id}/', [ValidacionesController::class, 'mod_ben_val']);
Route::post('validaciones/update', [ValidacionesController::class, 'update'])->name('validaciones.update');
Route::get('validaciones/destroy/{id}/', [ValidacionesController::class, 'destroy']);

Route::get('beneficiarios', [BeneficiariosController::class, 'index'])->middleware('can:admin.beneficiarios.index')->name('beneficiarios.index');
Route::get('beneficiarios/datatable', [BeneficiariosController::class, 'datatable'])->name('beneficiarios.datatable');
Route::post('beneficiarios/store', [BeneficiariosController::class, 'store'])->name('beneficiarios.store');
Route::get('beneficiarios/edit/{id}/', [BeneficiariosController::class, 'edit']);
Route::get('beneficiarios/duplicados/{id}/', [BeneficiariosController::class, 'duplicados']);
Route::get('beneficiarios/NumReg/{id}/', [BeneficiariosController::class, 'NumReg']);
Route::get('beneficiarios/VerProyectos/{id}/', [BeneficiariosController::class, 'VerProyectos']);
Route::get('beneficiarios/pdf/{id}/', [BeneficiariosController::class, 'pdf']);
Route::get('beneficiarios/excel', [BeneficiariosController::class, 'excel'])->name('beneficiarios.excel');
Route::post('beneficiarios/update', [BeneficiariosController::class, 'update'])->name('beneficiarios.update');
Route::get('beneficiarios/destroy/{id}/', [BeneficiariosController::class, 'destroy']);

Route::get('visitas', [VisitasController::class, 'index'])->middleware('can:admin.visitas.index')->name('visitas.index');
Route::post('visitas/store', [VisitasController::class, 'store'])->name('visitas.store');
Route::get('visitas/edit/{id}/', [VisitasController::class, 'edit']);
Route::post('visitas/update', [VisitasController::class, 'update'])->name('visitas.update');
Route::get('visitas/destroy/{id}/', [VisitasController::class, 'destroy']);

Route::get('fierros', [FierrosController::class, 'index'])->middleware('can:admin.fierros.index')->name('fierros.index');
Route::post('fierros/store', [FierrosController::class, 'store'])->name('fierros.store');
Route::post('fierros/update', [FierrosController::class, 'update'])->name('fierros.update');
Route::get('fierros/destroy/{id}/', [FierrosController::class, 'destroy']);
Route::get('fierros/edit/{id}/', [FierrosController::class, 'edit']);

Route::get('oficios', [OficiosController::class, 'index'])->middleware('can:admin.oficios.index')->name('oficios.index');
Route::get('oficios/datatable', [OficiosController::class, 'datatable'])->name('oficios.datatable');
Route::post('oficios/store', [OficiosController::class, 'store'])->name('oficios.store');
Route::post('oficios/update', [OficiosController::class, 'update'])->name('oficios.update');
Route::get('oficios/destroy/{id}/', [OficiosController::class, 'destroy']);
Route::get('oficios/edit/{id}/', [OficiosController::class, 'edit']);
Route::get('oficios/pdf/{id}/', [OficiosController::class, 'pdf']);


Route::get('comisiones', [ComisionController::class, 'index'])->middleware('can:admin.users')->name('comisiones.index');
Route::post('comisiones/store', [ComisionController::class, 'store'])->name('comisiones.store');
Route::post('comisiones/update', [ComisionController::class, 'update'])->name('comisiones.update');
Route::get('comisiones/destroy/{id}/', [ComisionController::class, 'destroy']);
Route::get('comisiones/edit/{id}/', [ComisionController::class, 'edit']);
Route::get('comisiones/word/{id}', [ComisionController::class, 'word'])->name('comisiones.word');
Route::get('comisiones/excel', [ComisionController::class, 'excel'])->name('comisiones.excel');

Route::get('productos', [ProductoController::class, 'index'])->middleware('can:admin.inventarios.index')->name('productos.index');
Route::get('productos/datatable', [ProductoController::class, 'datatable'])->name('productos.datatable');
Route::post('productos/store', [ProductoController::class, 'store'])->name('productos.store');
Route::post('productos/update', [ProductoController::class, 'update'])->name('productos.update');
Route::get('productos/datos', [ProductoController::class, 'datos']);


Route::post('inventarios/store', [InventarioController::class, 'store'])->name('inventarios.store');
/*Route::resource('admin',UserController::class)->middleware('auth');

Route::group(['middleware'=>'auth'],function(){
   Route::get('users/', [UserController::class, 'index'])->name('users');
});*/
