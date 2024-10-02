<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('loan_schedule', function (Blueprint $table) {
        $table->string('status', 50)->nullable(); 
    });
}

public function down()
{
    Schema::table('loan_schedule', function (Blueprint $table) {
        $table->dropColumn('status');
    });
}

};
