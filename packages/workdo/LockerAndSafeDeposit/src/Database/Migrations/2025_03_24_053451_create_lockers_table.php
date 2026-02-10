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
        if(!Schema::hasTable('lockers'))
        {
            Schema::create('lockers', function (Blueprint $table) {
                $table->id();
                $table->integer('locker_number');
                $table->string('locker_type');
                $table->string('locker_size');
                $table->string('max_capacity')->nullable();
                $table->float('price_of_month' , 15,2)->default(0.00);
                $table->float('price_of_year', 15 ,2)->default(0.00);
                $table->string('status');
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
        Schema::dropIfExists('lockers');
    }
};
