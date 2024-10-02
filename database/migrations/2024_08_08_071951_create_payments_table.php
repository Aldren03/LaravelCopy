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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('ref_no')->unique();
            $table->foreignId('borrower_id')->constrained()->onDelete('cascade');
            $table->foreignId('ledger_id')->constrained('ledger')->onDelete('cascade');
            $table->decimal('pay_amount', 15, 2);
            $table->decimal('penalty', 15, 2)->nullable(); 
            $table->boolean('overdue')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
