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
            // Change status column to string
            $table->string('status')->default('pending')->change();
        });
    }
    
    public function down(): void
    {
        Schema::table('ledger', function (Blueprint $table) {
            // Revert back to enum if needed
            $table->enum('status', [0, 1, 2, 3, 4])->default(0)->change();
        });
    }
};
