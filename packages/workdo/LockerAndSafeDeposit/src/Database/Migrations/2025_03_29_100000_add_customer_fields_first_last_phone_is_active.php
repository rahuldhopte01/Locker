<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add first_name, last_name, phone, is_active. Migrate from name/contact_no then drop them.
     */
    public function up(): void
    {
        if (!Schema::hasTable('locker_customers')) {
            return;
        }

        Schema::table('locker_customers', function (Blueprint $table) {
            if (!Schema::hasColumn('locker_customers', 'first_name')) {
                $table->string('first_name')->nullable()->after('id');
            }
            if (!Schema::hasColumn('locker_customers', 'last_name')) {
                $table->string('last_name')->nullable()->after('first_name');
            }
            if (!Schema::hasColumn('locker_customers', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
            if (!Schema::hasColumn('locker_customers', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('created_by');
            }
        });

        // Backfill from existing name and contact_no
        if (Schema::hasColumn('locker_customers', 'name')) {
            $rows = DB::table('locker_customers')->get();
            foreach ($rows as $row) {
                $parts = preg_split('/\s+/', trim($row->name ?? ''), 2);
                $firstName = $parts[0] ?? '';
                $lastName = $parts[1] ?? '';
                DB::table('locker_customers')
                    ->where('id', $row->id)
                    ->update([
                        'first_name' => $firstName,
                        'last_name'  => $lastName,
                        'phone'      => $row->contact_no ?? null,
                    ]);
            }
            Schema::table('locker_customers', function (Blueprint $table) {
                $table->dropColumn('name');
            });
        }
        if (Schema::hasColumn('locker_customers', 'contact_no')) {
            Schema::table('locker_customers', function (Blueprint $table) {
                $table->dropColumn('contact_no');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('locker_customers')) {
            return;
        }

        Schema::table('locker_customers', function (Blueprint $table) {
            $table->string('name')->nullable()->after('id');
            $table->string('contact_no')->nullable()->after('email');
        });

        $rows = DB::table('locker_customers')->get();
        foreach ($rows as $row) {
            $name = trim(($row->first_name ?? '') . ' ' . ($row->last_name ?? ''));
            DB::table('locker_customers')
                ->where('id', $row->id)
                ->update([
                    'name'       => $name ?: 'N/A',
                    'contact_no' => $row->phone ?? null,
                ]);
        }

        Schema::table('locker_customers', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name', 'phone', 'is_active']);
        });
    }
};
