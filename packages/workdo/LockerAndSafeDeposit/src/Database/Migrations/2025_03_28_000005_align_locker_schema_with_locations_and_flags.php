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
        Schema::table('lockers', function (Blueprint $table) {
            if (!Schema::hasColumn('lockers', 'location_id')) {
                $table->unsignedBigInteger('location_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('lockers', 'is_available')) {
                $table->boolean('is_available')->default(true)->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lockers', function (Blueprint $table) {
            if (Schema::hasColumn('lockers', 'location_id')) {
                $table->dropColumn('location_id');
            }
            if (Schema::hasColumn('lockers', 'is_available')) {
                $table->dropColumn('is_available');
            }
        });
    }
};
