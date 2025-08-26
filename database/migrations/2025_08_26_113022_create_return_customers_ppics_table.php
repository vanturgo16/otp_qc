<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class  CreateReturnCustomersPpicsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('return_customers_ppic', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_delivery_note_details')->nullable();
            $table->unsignedBigInteger('id_delivery_notes')->nullable();
            $table->unsignedBigInteger('id_master_customers')->nullable();
            $table->string('no_po')->nullable();
            $table->unsignedBigInteger('id_sales_orders')->nullable();
            $table->string('name')->nullable(); // Nama produk
            $table->decimal('qty', 10, 3)->nullable();
            $table->unsignedBigInteger('id_master_units')->nullable();
            $table->date('tanggal')->nullable();
            $table->integer('berat')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

    
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_customers_ppic');
    }
}