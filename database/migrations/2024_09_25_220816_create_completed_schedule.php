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
        Schema::create('completed_schedule', function (Blueprint $table) {
            $table->id();
            $table->foreignId('completed_ledger_id')->constrained('completed_ledgers')->onDelete('cascade');
            $table->string('status')->default('paid');
            $table->date('date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('completed_schedule');
    }
};