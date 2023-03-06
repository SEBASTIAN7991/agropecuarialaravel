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
        Schema::create('validaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('Id_Sol');
            $table->unsignedBigInteger('Id_Pro');
            $table->foreign("Id_Sol")->references("id")->on("solicitudes")->onDelete("cascade")->onUpdate("cascade");
            $table->String('Resp_Valid');
            $table->date('Fecha_Val_Inicio');
            $table->date('Fecha_Val_Termino');
            $table->unsignedBigInteger('Cant_Validado');
            $table->unsignedBigInteger('Ben_H_Validado');
            $table->unsignedBigInteger('Ben_M_Validado');
            $table->unsignedBigInteger('Estatus');
            $table->unsignedBigInteger('Verificado');
            $table->unsignedBigInteger('Id_Usuario');
            $table->String('Tipo_Asignacion');
            $table->bigInteger('Of_Control');
            $table->timestamp('Fecha_Entrega');
            $table->text('Comentario');
            $table->string('Porcentaje');
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
        Schema::dropIfExists('validaciones');
    }
};
