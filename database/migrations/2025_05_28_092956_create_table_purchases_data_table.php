<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('vendors') && Schema::hasTable('purchases')) {
            $driver = \DB::connection()->getDriverName();
            if ($driver === 'sqlite') {
                \DB::statement('
                    UPDATE purchases
                    SET vender_id = (
                        SELECT id FROM vendors
                        WHERE vendors.user_id = purchases.user_id
                        LIMIT 1
                    )
                ');
            } else {
                \DB::statement("
                    UPDATE purchases
                    JOIN vendors ON purchases.user_id = vendors.user_id
                    SET purchases.vender_id = vendors.id
                ");
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_purchases_data');
    }
};
