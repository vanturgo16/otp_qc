<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataWasteTable extends Migration
{
    public function up()
    {
        Schema::create('data_waste', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_resource')->nullable();
            $table->string('id_resource_column')->nullable();
            $table->string('status')->nullable();
            $table->text('remark')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('data_waste');
    }
}