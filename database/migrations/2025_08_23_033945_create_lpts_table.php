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
    $table->string('no_lpts');
    $table->unsignedBigInteger('id_wo');
    $table->unsignedBigInteger('id_history_stock')->nullable();
    $table->unsignedBigInteger('id_master_products')->nullable();
    $table->string('no_wo');
    $table->string('type_product')->nullable();
    $table->integer('qty')->default(0);
    $table->integer('weight')->default(0);
    $table->string('barcode_number');
    $table->string('qc_status')->default('checked');
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
