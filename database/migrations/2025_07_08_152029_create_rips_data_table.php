<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rips_data', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->integer('month');
            $table->string('regimen');
            $table->decimal('facturado', 15, 2);
            $table->integer('consultas_especializada')->default(0);
            $table->integer('interconsultas_hospitalaria')->default(0);
            $table->integer('urgencias_general')->default(0);
            $table->integer('urgencias_especialista')->default(0);
            $table->integer('egresos_hospitalarios')->default(0);
            $table->integer('imagenologia')->default(0);
            $table->integer('laboratorio')->default(0);
            $table->integer('partos')->default(0);
            $table->integer('cesareas')->default(0);
            $table->integer('cirugias')->default(0);
            $table->integer('terapia_fisica')->default(0);
            $table->integer('terapia_respiratoria')->default(0);
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rips_data');
    }
};