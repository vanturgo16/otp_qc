<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToLptsTable extends Migration
{
    public function up()
    {
        Schema::table('lpts', function (Blueprint $table) {
            $table->unsignedBigInteger('id_history_stock')->nullable()->after('id_wo');
            $table->string('qc_status')->default('checked')->after('keterangan');
        });
    }

    public function down()
    {
        Schema::table('lpts', function (Blueprint $table) {
            $table->dropColumn('id_history_stock');
            $table->dropColumn('qc_status');
        });
    }
}