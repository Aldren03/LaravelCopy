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
        Schema::create('completed_ledgers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('home_address');
            $table->string('contact_number', 15);
            $table->foreignId('loan_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('loan_plan_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->date('start_date');
            $table->date('end_date');
            $table->string('status')->default('completed');
            $table->unsignedBigInteger('ledger_id')->nullable();
            $table->timestamps();

            // Foreign key constraints

            $table->foreign('ledger_id')->references('id')->on('ledger')->onDelete('set null');

        });
    }

    public function down()
    {
        Schema::dropIfExists('completed_ledgers');
    }
};
