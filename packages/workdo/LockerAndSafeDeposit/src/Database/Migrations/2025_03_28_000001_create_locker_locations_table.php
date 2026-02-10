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
        if (!Schema::hasTable('locker_locations'))
        {
            Schema::create('locker_locations', function (Blueprint $table) {
                $table->id();
                $table->string('building');
                $table->string('floor')->nullable();
                $table->string('section')->nullable();
                $table->text('address')->nullable();
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
        Schema::dropIfExists('locker_locations');
    }
};
