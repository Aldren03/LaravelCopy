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
        Schema::create('ledger', function (Blueprint $table) {
            $table->id();
            $table->string('ref_no')->unique();
            $table->foreignId('borrower_id')->constrained()->onDelete('cascade');
            $table->foreignId('loan_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('loan_plan_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->text('purpose');
            $table->enum('status', [0, 1, 2, 3, 4])->default(0);
            $table->date('date_released')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ledger');
    }
};
