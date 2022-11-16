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
        Schema::create('beneficiarios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('Id_Val');
            $table->foreign("Id_Val")->references("id")->on("validaciones")->onDelete("cascade")->onUpdate("cascade");
            $table->unsignedBigInteger('Id_Sol');
            $table->foreign("Id_Sol")->references("id")->on("solicitudes")->onDelete("cascade")->onUpdate("cascade");
            $table->unsignedBigInteger('Id_Pro');
            $table->foreign("Id_Pro")->references("id")->on("proyectos")->onDelete("cascade")->onUpdate("cascade");
            $table->String('Nom_Ben');
            $table->String('Pat_Ben');
            $table->String('Mat_Ben');
            $table->String('Sexo');
            $table->String('Clave_El');
            $table->String('Curp');
            $table->unsignedBigInteger('Id_Loc');
            $table->foreign("Id_Loc")->references("id")->on("localidades")->onDelete("cascade")->onUpdate("cascade");
            $table->unsignedBigInteger('Id_Reg');
            $table->foreign("Id_Reg")->references("id")->on("regiones")->onDelete("cascade")->onUpdate("cascade");
            $table->String('Estatus');
            $table->unsignedBigInteger('Estatus2');
            $table->unsignedBigInteger('Id_Usuario');
            $table->String('Documentos');
             $table->text('Comentario');
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
        Schema::dropIfExists('beneficiarios');
    }
};
