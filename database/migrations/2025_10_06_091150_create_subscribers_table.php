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

            $table->dateTime('date_subscription');
            $table->dateTime('date_last_status_update');
            $table->dateTime('date_end_trial_period');
            $table->dateTime('date_last_unsub');
            $table->dateTime('date_first_success_payment');

            $table->string('billing_status');
            $table->dateTime('date_expired');

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
