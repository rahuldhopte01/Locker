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
        if (!Schema::hasTable('locker_notification_logs'))
        {
            Schema::create('locker_notification_logs', function (Blueprint $table) {
                $table->id();
                $table->string('channel'); // email, sms
                $table->string('recipient');
                $table->string('subject')->nullable();
                $table->text('message')->nullable();
                $table->string('status')->default('pending'); // pending, sent, failed, delivered
                $table->string('reference_type')->nullable(); // LockerBooking, LockerRenewal, etc.
                $table->unsignedBigInteger('reference_id')->nullable();
                $table->text('error_message')->nullable();
                $table->timestamp('sent_at')->nullable();
                $table->timestamp('delivered_at')->nullable();
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
        Schema::dropIfExists('locker_notification_logs');
    }
};
