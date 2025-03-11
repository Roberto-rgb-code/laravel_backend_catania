<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUniformesTable extends Migration
{
    public function up()
    {
        Schema::create('uniformes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->nullable(false);
            $table->string('descripcion')->nullable(false);
            $table->string('categoria')->nullable(false);
            $table->string('foto_path')->nullable(); // Solo una foto
            $table->string('tipo')->nullable(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('uniformes');
    }
}