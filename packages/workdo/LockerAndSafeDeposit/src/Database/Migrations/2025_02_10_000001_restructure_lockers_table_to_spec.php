<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Restructure lockers table to match spec 4.1.2:
     * locker_number VARCHAR(20) unique, location_id, size ENUM, status ENUM,
     * monthly_rate DECIMAL(10,2), is_available BOOLEAN
     */
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        Schema::table('lockers', function (Blueprint $table) {
            if (!Schema::hasColumn('lockers', 'monthly_rate')) {
                $table->decimal('monthly_rate', 10, 2)->default(0.00)->after('status');
            }
            if (!Schema::hasColumn('lockers', 'size')) {
                $table->string('size', 20)->nullable()->after('location_id');
            }
        });

        // Migrate existing data before dropping columns
        if (Schema::hasColumn('lockers', 'price_of_month')) {
            DB::table('lockers')->whereNull('monthly_rate')->update([
                'monthly_rate' => DB::raw('COALESCE(price_of_month, 0)'),
            ]);
        }
        if (Schema::hasColumn('lockers', 'locker_size')) {
            DB::table('lockers')->whereNull('size')->update([
                'size' => DB::raw('LOWER(TRIM(locker_size))'),
            ]);
        }
        if (Schema::hasColumn('lockers', 'status')) {
            DB::table('lockers')->where('status', 'Available')->update(['status' => 'active']);
            DB::table('lockers')->where('status', 'UnAvailable')->update(['status' => 'inactive']);
        }

        // Drop old columns if they exist
        Schema::table('lockers', function (Blueprint $table) {
            if (Schema::hasColumn('lockers', 'locker_type')) {
                $table->dropColumn('locker_type');
            }
            if (Schema::hasColumn('lockers', 'locker_size')) {
                $table->dropColumn('locker_size');
            }
            if (Schema::hasColumn('lockers', 'max_capacity')) {
                $table->dropColumn('max_capacity');
            }
            if (Schema::hasColumn('lockers', 'price_of_month')) {
                $table->dropColumn('price_of_month');
            }
            if (Schema::hasColumn('lockers', 'price_of_year')) {
                $table->dropColumn('price_of_year');
            }
        });

        // Change locker_number to VARCHAR(20) unique (MySQL)
        if ($driver === 'mysql' && Schema::hasColumn('lockers', 'locker_number')) {
            DB::statement('ALTER TABLE lockers MODIFY locker_number VARCHAR(20) NOT NULL');
            $lockers = DB::table('lockers')->get();
            foreach ($lockers as $l) {
                $num = is_numeric($l->locker_number) ? sprintf('%05d', (int) $l->locker_number) : (string) $l->locker_number;
                DB::table('lockers')->where('id', $l->id)->update(['locker_number' => $num]);
            }
            try {
                DB::statement('ALTER TABLE lockers ADD UNIQUE lockers_locker_number_unique (locker_number)');
            } catch (\Throwable $e) {
                // Unique index may already exist
            }
        }

        // Ensure size has default where null
        DB::table('lockers')->whereNull('size')->orWhere('size', '')->update(['size' => 'medium']);
        if ($driver === 'mysql' && Schema::hasColumn('lockers', 'size')) {
            DB::statement("ALTER TABLE lockers MODIFY size VARCHAR(20) NOT NULL DEFAULT 'medium'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lockers', function (Blueprint $table) {
            if (Schema::hasColumn('lockers', 'monthly_rate')) {
                $table->dropColumn('monthly_rate');
            }
            if (Schema::hasColumn('lockers', 'size')) {
                $table->dropColumn('size');
            }
        });
        Schema::table('lockers', function (Blueprint $table) {
            if (!Schema::hasColumn('lockers', 'locker_type')) {
                $table->string('locker_type')->nullable()->after('locker_number');
            }
            if (!Schema::hasColumn('lockers', 'locker_size')) {
                $table->string('locker_size')->nullable()->after('locker_type');
            }
            if (!Schema::hasColumn('lockers', 'max_capacity')) {
                $table->string('max_capacity')->nullable()->after('locker_size');
            }
            if (!Schema::hasColumn('lockers', 'price_of_month')) {
                $table->float('price_of_month', 15, 2)->default(0)->after('max_capacity');
            }
            if (!Schema::hasColumn('lockers', 'price_of_year')) {
                $table->float('price_of_year', 15, 2)->default(0)->after('price_of_month');
            }
        });
    }
};
