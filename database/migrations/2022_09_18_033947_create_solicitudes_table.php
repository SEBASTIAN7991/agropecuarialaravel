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
    Schema::create('solicitudes', function (Blueprint $table) {
    $table->id();
    $table->String('Folio');
    $table->unsignedBigInteger('Id_Org');
    $table->foreign("Id_Org")->references("id")->on("organizaciones")->onDelete("cascade")->onUpdate("cascade");
    $table->unsignedBigInteger('Id_Cargo');
    $table->foreign("Id_Cargo")->references("id")->on("cargos")->onDelete("cascade")->onUpdate("cascade");
    $table->date('Fecha_Sol');
    $table->unsignedBigInteger('Id_Loc');
    $table->foreign("Id_Loc")->references("id")->on("localidades")->onDelete("cascade")->onUpdate("cascade");
    $table->unsignedBigInteger('Id_Reg');
    $table->foreign("Id_Reg")->references("id")->on("regiones")->onDelete("cascade")->onUpdate("cascade");
    $table->unsignedBigInteger('Id_Pro');
    $table->foreign("Id_Pro")->references("id")->on("proyectos")->onDelete("cascade")->onUpdate("cascade");
    $table->String('Subrepresentante');
    $table->String('Telefono');
    $table->unsignedBigInteger('Cant_Sol');
    $table->unsignedBigInteger('Ben_H');
    $table->unsignedBigInteger('Ben_M');
    $table->String('Ubicacion_Archivo');
    $table->String('Convenio');
    $table->String('Tipo_Convenio');
    $table->unsignedBigInteger('Validado');
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
        Schema::dropIfExists('solicitudes');
    }
};
