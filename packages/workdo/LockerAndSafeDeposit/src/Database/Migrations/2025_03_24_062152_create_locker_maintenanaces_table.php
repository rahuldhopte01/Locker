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
        if(!Schema::hasTable('locker_maintenances'))
        {
            Schema::create('locker_maintenances', function (Blueprint $table) {
                $table->id();
                $table->integer('locker_id');
                $table->string('technician_name');
                $table->string('repair_status');
                $table->date('reported_date');
                $table->date('repair_date');
                $table->longText('description')->nullable();
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
        Schema::dropIfExists('locker_maintenances');
    }
};
