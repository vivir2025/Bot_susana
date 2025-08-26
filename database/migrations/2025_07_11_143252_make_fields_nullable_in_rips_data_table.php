<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('rips_data', function (Blueprint $table) {
            // Campos numéricos que pueden ser NULL
            $table->bigInteger('consultas_especializada')->nullable()->default(null)->change();
            $table->bigInteger('interconsultas_hospitalaria')->nullable()->default(null)->change();
            $table->bigInteger('urgencias_general')->nullable()->default(null)->change();
            $table->bigInteger('urgencias_especialista')->nullable()->default(null)->change();
            $table->bigInteger('egresos_hospitalarios')->nullable()->default(null)->change();
            $table->bigInteger('imagenologia')->nullable()->default(null)->change();
            $table->bigInteger('laboratorio')->nullable()->default(null)->change();
            $table->bigInteger('partos')->nullable()->default(null)->change();
            $table->bigInteger('cesareas')->nullable()->default(null)->change();
            $table->bigInteger('cirugias')->nullable()->default(null)->change();
            $table->bigInteger('terapia_fisica')->nullable()->default(null)->change();
            $table->bigInteger('terapia_respiratoria')->nullable()->default(null)->change();
            $table->bigInteger('observaciones')->nullable()->default(null)->change();
            
            // Campo facturado (si también puede ser NULL)
            $table->bigInteger('facturado')->nullable()->default(null)->change();
        });
    }

    public function down()
    {
        Schema::table('rips_data', function (Blueprint $table) {
            // Revertir los cambios si es necesario
            $table->bigInteger('consultas_especializada')->default(0)->change();
            $table->bigInteger('interconsultas_hospitalaria')->default(0)->change();
            $table->bigInteger('urgencias_general')->default(0)->change();
            $table->bigInteger('urgencias_especialista')->default(0)->change();
            $table->bigInteger('egresos_hospitalarios')->default(0)->change();
            $table->bigInteger('imagenologia')->default(0)->change();
            $table->bigInteger('laboratorio')->default(0)->change();
            $table->bigInteger('partos')->default(0)->change();
            $table->bigInteger('cesareas')->default(0)->change();
            $table->bigInteger('cirugias')->default(0)->change();
            $table->bigInteger('terapia_fisica')->default(0)->change();
            $table->bigInteger('terapia_respiratoria')->default(0)->change();
            $table->bigInteger('observaciones')->default(0)->change();
            $table->bigInteger('facturado')->default(0)->change();
        });
    }
};