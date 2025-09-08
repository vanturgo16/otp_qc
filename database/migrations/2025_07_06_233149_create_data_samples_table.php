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
        Schema::create('data_samples', function (Blueprint $table) {
            $table->id();
            $table->integer('id_so');
            $table->string('no_sample');
            $table->date('sample_done_date');
            $table->string('sample_type');
            $table->string('product_type');
            $table->date('sample_submission_date')->nullable();
            $table->integer('done_duration');
            $table->text('remarks')->nullable();
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_samples');
    }
};
