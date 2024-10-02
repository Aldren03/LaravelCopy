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
        Schema::table('loan_schedule', function (Blueprint $table) {
            $table->boolean('paid')->default(0); 
            $table->date('payment_date')->nullable(); 
            $table->decimal('payment_amount', 15, 2)->nullable(); 
            $table->decimal('penalty', 15, 2)->nullable(); 
        });
    }
    
    public function down(): void
    {
        Schema::table('loan_schedule', function (Blueprint $table) {
            $table->dropColumn('paid');
            $table->dropColumn('payment_date');
            $table->dropColumn('payment_amount');
            $table->dropColumn('penalty');
        });
    }
};
