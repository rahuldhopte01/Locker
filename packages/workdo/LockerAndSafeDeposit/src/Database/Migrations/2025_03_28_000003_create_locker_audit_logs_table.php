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
        if (!Schema::hasTable('locker_audit_logs'))
        {
            Schema::create('locker_audit_logs', function (Blueprint $table) {
                $table->id();
                $table->string('action'); // created, updated, deleted, renewed, payment_received, etc.
                $table->string('auditable_type'); // Locker, LockerBooking, LockerCustomer, etc.
                $table->unsignedBigInteger('auditable_id');
                $table->text('old_values')->nullable(); // JSON snapshot before change
                $table->text('new_values')->nullable(); // JSON snapshot after change
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->integer('workspace')->nullable();
                $table->timestamps();

                $table->index(['auditable_type', 'auditable_id']);
                $table->index(['action', 'created_at']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locker_audit_logs');
    }
};
