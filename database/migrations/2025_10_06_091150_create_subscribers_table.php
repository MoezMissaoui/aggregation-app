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

            $table->timestamp('date_subscription')->nullable();
            $table->timestamp('date_last_status_update')->nullable();
            $table->timestamp('date_end_trial_period')->nullable();
            $table->timestamp('date_last_unsub')->nullable();
            $table->timestamp('date_first_success_payment')->nullable();

            $table->string('billing_status')->nullable();
            $table->timestamp('date_expired')->nullable();

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
