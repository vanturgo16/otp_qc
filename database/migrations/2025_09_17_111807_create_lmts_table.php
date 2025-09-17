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
        Schema::create('lmts', function (Blueprint $table) {
            $table->id();
            $table->string('no_lmts')->nullable();
            $table->unsignedBigInteger('id_good_receipt_notes')->nullable();
            $table->string('receipt_number')->nullable();
            $table->string('lot_number')->nullable();
            $table->unsignedBigInteger('id_good_receipt_notes_details')->nullable();
            $table->unsignedBigInteger('id_master_products')->nullable();
            $table->text('description')->nullable();
            $table->string('external_lot')->nullable();
            $table->date('date')->nullable();
            $table->decimal('total_glq', 10, 3)->nullable();
            $table->unsignedBigInteger('id_master_units')->nullable();
            $table->string('type_product')->nullable();
            $table->string('status')->nullable();
            $table->text('remarks')->nullable();
            $table->string('unit')->nullable();
            $table->timestamps();

            // // Add foreign key constraints if needed
            // $table->foreign('id_good_receipt_notes')->references('id')->on('good_receipt_notes')->onDelete('cascade');
            // $table->foreign('id_good_receipt_notes_details')->references('id')->on('good_receipt_note_details')->onDelete('cascade');
            // $table->foreign('id_master_products')->references('id')->on('master_product_fgs')->onDelete('cascade');
            // $table->foreign('id_master_units')->references('id')->on('master_units')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lmts');
    }
};
