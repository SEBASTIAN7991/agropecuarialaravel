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
        Schema::create('visitas', function (Blueprint $table) {
            $table->id();
            $table->String('Nombre');
            $table->String('Paterno');
            $table->String('Materno');
            $table->unsignedBigInteger('Id_Cargo');
            $table->foreign("Id_Cargo")->references("id")->on("cargos")->onDelete("cascade")->onUpdate("cascade");
            $table->unsignedBigInteger('Id_Loc');
            $table->foreign("Id_Loc")->references("id")->on("localidades")->onDelete("cascade")->onUpdate("cascade");
            $table->unsignedBigInteger('Id_Reg');
            $table->foreign("Id_Reg")->references("id")->on("regiones")->onDelete("cascade")->onUpdate("cascade");
            $table->String('Nom_Org');
            $table->String('Telefono');
            $table->date('Fecha_Visita');
            $table->String('Hora_Ingreso');
            $table->String('Asunto');
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
        Schema::dropIfExists('visitas');
    }
};
