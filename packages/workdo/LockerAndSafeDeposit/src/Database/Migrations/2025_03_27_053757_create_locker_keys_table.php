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
        if(!Schema::hasTable('locker_keys'))
        {
            Schema::create('locker_keys', function (Blueprint $table) {
                $table->id();
                $table->integer('locker_id');
                $table->integer('customer_id');
                $table->string('key_type');
                $table->date('issue_date');
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
        Schema::dropIfExists('locker_keys');
    }
};
