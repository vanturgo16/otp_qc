<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lpts', function (Blueprint $table) {
          $table->id();
    $table->string('no_lpts')->unique();
    $table->unsignedBigInteger('id_wo');
    $table->text('keterangan')->nullable();
    $table->string('created_by')->nullable();
    $table->timestamps(); // created_at & updated_at otomatis
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lpts');
    }
};
