<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users_by_age', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->integer('month');
            $table->integer('age_group_0_1')->default(0);
            $table->integer('age_group_1_4')->default(0);
            $table->integer('age_group_5_14')->default(0);
            $table->integer('age_group_15_44')->default(0);
            $table->integer('age_group_45_59')->default(0);
            $table->integer('age_group_60_plus')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users_by_age');
    }
};