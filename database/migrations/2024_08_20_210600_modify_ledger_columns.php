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
    Schema::table('ledger', function (Blueprint $table) {
        $table->unsignedBigInteger('borrower_id')->nullable()->change();
        $table->unsignedBigInteger('borrower_form_id')->nullable()->change();
    });
}

public function down()
{
    Schema::table('ledger', function (Blueprint $table) {
        $table->unsignedBigInteger('borrower_id')->nullable(false)->change();
        $table->unsignedBigInteger('borrower_form_id')->nullable(false)->change();
    });
}

};
