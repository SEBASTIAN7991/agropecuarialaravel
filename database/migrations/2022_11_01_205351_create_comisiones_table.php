<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comisiones', function (Blueprint $table) {
            $table->id();
            $table->date('Fecha_Emision');
            $table->unsignedBigInteger('Id_Comisionado');
            $table->foreign("Id_Comisionado")->references("id")->on("personas")->onDelete("cascade")->onUpdate("cascade");
            $table->String('Loc_Destino');
            $table->String('Fecha_Comision');
            $table->text('Actividad');
            $table->String('Tipo_Trans');
            $table->String('Placas');
            $table->String('Num_Eco');
            $table->String('Programa');
            $table->text('Comentario');
            $table->bigInteger('Estatus');
            $table->bigInteger('Dias')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comisiones');
    }
};
