<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUniformeFotosTable extends Migration
{
    public function up()
    {
        Schema::create('uniforme_fotos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('uniforme_id')->constrained('uniformes')->onDelete('cascade');
            $table->string('foto_path');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('uniforme_fotos');
    }
}
