<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Complete history per locker: when rented, to whom, payments, reminders, overdue notices, etc.
     */
    public function up(): void
    {
        if (!Schema::hasTable('locker_locker_history')) {
            Schema::create('locker_locker_history', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('locker_id');
                $table->unsignedBigInteger('rental_id')->nullable();
                $table->string('event_type', 60); // rental_started, rental_ended, payment_received, reminder_sent, overdue_notification_sent, status_changed, etc.
                $table->text('description')->nullable();
                // Optional link to related record (e.g. payment id, notification log id)
                $table->string('related_type', 60)->nullable();
                $table->unsignedBigInteger('related_id')->nullable();
                // Extra data (amount, payment_method, customer_id, channel, etc.)
                $table->json('metadata')->nullable();
                $table->integer('workspace')->nullable();
                $table->integer('created_by')->default(0);
                $table->timestamp('occurred_at')->useCurrent();

                $table->foreign('locker_id')->references('id')->on('lockers')->onDelete('cascade');
                $table->foreign('rental_id')->references('id')->on('locker_rentals')->onDelete('set null');
                $table->index(['locker_id', 'occurred_at']);
                $table->index('event_type');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locker_locker_history');
    }
};
