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
        Schema::create('fierros', function (Blueprint $table) {
            $table->id();
            $table->date('Fecha_Tramite');
            $table->String('Elaborado_Por');
            $table->unsignedBigInteger('Id_Loc');
            $table->foreign("Id_Loc")->references("id")->on("localidades")->onDelete("cascade")->onUpdate("cascade");
            $table->unsignedBigInteger('Id_Reg');
            $table->foreign("Id_Reg")->references("id")->on("regiones")->onDelete("cascade")->onUpdate("cascade");
            $table->String('Nombre');
            $table->String('Paterno');
            $table->String('Materno');
            $table->String('Curp');
            $table->String('Rfc');
            $table->unsignedBigInteger('Edad');
            $table->String('Sexo');
            $table->String('Tipo_Tramite');
            $table->String('Upp');
            $table->String('Folio_Pago');
            $table->unsignedBigInteger('Total');
            $table->String('Estatus');
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
        Schema::dropIfExists('fierros');
    }
};
