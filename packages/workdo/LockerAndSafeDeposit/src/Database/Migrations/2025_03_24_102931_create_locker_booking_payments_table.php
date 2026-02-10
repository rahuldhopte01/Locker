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
        if(!Schema::hasTable('locker_booking_payments'))
        {
            Schema::create('locker_booking_payments', function (Blueprint $table) {
                $table->id();
                $table->integer('booking_id');
                $table->date('date');
                $table->float('amount',15,2)->default(0.00);
                $table->text('description')->nullable();
                $table->string('receipt')->nullable();
                $table->integer('workspace')->nullable();
                $table->integer('created_by')->default(0);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locker_booking_payments');
    }
};
