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
            $table->bigInteger('facturado')->default(0);
            $table->bigInteger('consultas_especializada')->default(0);
            $table->bigInteger('interconsultas_hospitalaria')->default(0);
            $table->bigInteger('urgencias_general')->default(0);
            $table->bigInteger('urgencias_especialista')->default(0);
            $table->bigInteger('egresos_hospitalarios')->default(0);
            $table->bigInteger('imagenologia')->default(0);
            $table->bigInteger('laboratorio')->default(0);
            $table->bigInteger('partos')->default(0);
            $table->bigInteger('cesareas')->default(0);
            $table->bigInteger('cirugias')->default(0);
            $table->bigInteger('terapia_fisica')->default(0);
            $table->bigInteger('terapia_respiratoria')->default(0);
            $table->bigInteger('observaciones')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rips_data');
    }
};