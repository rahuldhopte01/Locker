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
        if(!Schema::hasTable('locker_memberships'))
        {
            Schema::create('locker_memberships', function (Blueprint $table) {
                $table->id();
                $table->integer('locker_id');
                $table->integer('customer_id');
                $table->date('start_date');
                $table->string('membership_type');
                $table->string('duration');
                $table->float('membership_fee',15,2)->default(0.00);
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
        Schema::dropIfExists('locker_memberships');
    }
};
