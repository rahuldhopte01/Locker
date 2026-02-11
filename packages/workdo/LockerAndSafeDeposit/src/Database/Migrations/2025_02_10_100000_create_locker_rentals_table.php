<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Rental table: which customer has which locker, dates, payment status,
     * and how they pay monthly (online/cash, full/partial).
     */
    public function up(): void
    {
        if (!Schema::hasTable('locker_rentals')) {
            Schema::create('locker_rentals', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('locker_id');
                $table->unsignedBigInteger('customer_id');
                $table->date('start_date');
                $table->date('end_date')->nullable(); // NULL if ongoing
                $table->string('payment_status', 20)->default('unpaid'); // paid, unpaid, overdue, partial
                $table->date('last_payment_date')->nullable();
                $table->date('next_payment_due')->nullable();
                // How the customer pays monthly: online vs cash
                $table->string('payment_method', 20)->nullable(); // online, cash
                // Whether they typically pay in full or partial each month
                $table->string('payment_type', 20)->nullable(); // full, partial
                $table->decimal('monthly_amount', 15, 2)->default(0);
                $table->integer('workspace')->nullable();
                $table->integer('created_by')->default(0);
                $table->timestamps();

                $table->foreign('locker_id')->references('id')->on('lockers')->onDelete('cascade');
                $table->foreign('customer_id')->references('id')->on('locker_customers')->onDelete('cascade');
                $table->index(['locker_id', 'end_date']);
                $table->index(['customer_id', 'payment_status']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locker_rentals');
    }
};
