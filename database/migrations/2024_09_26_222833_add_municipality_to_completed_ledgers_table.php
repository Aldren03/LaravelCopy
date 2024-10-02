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
    Schema::table('completed_ledgers', function (Blueprint $table) {
        $table->string('municipality')->nullable(); // Or change nullable to false if required
    });
}

public function down()
{
    Schema::table('completed_ledgers', function (Blueprint $table) {
        $table->dropColumn('municipality');
    });
}

};
