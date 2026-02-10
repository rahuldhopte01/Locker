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
        Schema::table('locker_bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('locker_bookings', 'end_date')) {
                $table->date('end_date')->nullable()->after('start_date');
            }
            if (!Schema::hasColumn('locker_bookings', 'payment_status')) {
                $table->string('payment_status')->default('pending')->after('amount'); // pending, partial, paid, overdue
            }
            if (!Schema::hasColumn('locker_bookings', 'last_payment_date')) {
                $table->date('last_payment_date')->nullable()->after('payment_status');
            }
            if (!Schema::hasColumn('locker_bookings', 'next_payment_due')) {
                $table->date('next_payment_due')->nullable()->after('last_payment_date');
            }
            if (!Schema::hasColumn('locker_bookings', 'reservation_date')) {
                $table->timestamp('reservation_date')->nullable()->after('next_payment_due');
            }
            if (!Schema::hasColumn('locker_bookings', 'reservation_expires_at')) {
                $table->timestamp('reservation_expires_at')->nullable()->after('reservation_date');
            }
            if (!Schema::hasColumn('locker_bookings', 'is_reservation')) {
                $table->boolean('is_reservation')->default(false)->after('reservation_expires_at');
            }
            if (!Schema::hasColumn('locker_bookings', 'reservation_status')) {
                $table->string('reservation_status')->nullable()->after('is_reservation'); // pending, confirmed, expired, converted
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('locker_bookings', function (Blueprint $table) {
            $columns = [
                'end_date', 'payment_status', 'last_payment_date', 'next_payment_due',
                'reservation_date', 'reservation_expires_at', 'is_reservation', 'reservation_status'
            ];
            foreach ($columns as $column) {
                if (Schema::hasColumn('locker_bookings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
