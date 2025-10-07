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
        Schema::create('subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('msisdn');

            $table->dateTime('date_subscription')->nullable();
            $table->dateTime('date_last_status_update')->nullable();
            $table->dateTime('date_end_trial_period')->nullable();
            $table->dateTime('date_last_unsub')->nullable();
            $table->dateTime('date_first_success_payment')->nullable();

            $table->string('billing_status')->nullable();
            $table->dateTime('date_expired')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscribers');
    }
};
