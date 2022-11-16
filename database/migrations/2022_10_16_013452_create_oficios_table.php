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
        Schema::create('oficios', function (Blueprint $table) {
            $table->id();
            $table->String('Estatus');
            $table->String('Oficio');
            $table->String('Num_Oficio');
            $table->date('Fecha');
            $table->text('Descripcion');
            $table->unsignedBigInteger('Id_Area');
            $table->foreign("Id_Area")->references("id")->on("areas")->onDelete("cascade")->onUpdate("cascade");
            $table->String('Ubicacion_Archivo');
            $table->String('Recibido_Por');
            $table->String('Enviado_Por');
            $table->unsignedBigInteger('Id_Usuario');
            $table->date('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->date('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('oficios');
    }
};
