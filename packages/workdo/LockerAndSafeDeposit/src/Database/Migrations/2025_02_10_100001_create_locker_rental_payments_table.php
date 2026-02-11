<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Each monthly (or other) payment for a rental: amount, date, method (online/cash), type (full/partial).
     */
    public function up(): void
    {
        if (!Schema::hasTable('locker_rental_payments')) {
            Schema::create('locker_rental_payments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('rental_id');
                $table->date('payment_date');
                $table->decimal('amount', 15, 2)->default(0);
                $table->string('payment_method', 20); // online, cash
                $table->string('payment_type', 20); // full, partial
                $table->string('receipt')->nullable();
                $table->text('notes')->nullable();
                $table->integer('workspace')->nullable();
                $table->integer('created_by')->default(0);
                $table->timestamps();

                $table->foreign('rental_id')->references('id')->on('locker_rentals')->onDelete('cascade');
                $table->index(['rental_id', 'payment_date']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locker_rental_payments');
    }
};
