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
        if(!Schema::hasTable('locker_customers'))
        {
            Schema::create('locker_customers', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('contact_no')->nullable();
                $table->string('email');
                $table->longText('address')->nullable();
                $table->longText('id_proof')->nullable();
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
        Schema::dropIfExists('locker_customers');
    }
};
