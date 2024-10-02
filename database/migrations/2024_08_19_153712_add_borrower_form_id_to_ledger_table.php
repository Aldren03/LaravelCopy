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
        Schema::table('ledger', function (Blueprint $table) {
        $table->unsignedBigInteger('borrower_form_id')->nullable();

            // Set up the foreign key constraint
            $table->foreign('borrower_form_id')
                  ->references('id')
                  ->on('borrower_forms')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ledger', function (Blueprint $table) {
  
            $table->dropForeign(['borrower_form_id']);
            $table->dropColumn('borrower_form_id');
        });
    }
};
