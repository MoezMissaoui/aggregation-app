<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\SubscriptionStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('subscriber_id')->nullable();
            $table->foreign('subscriber_id')->references('id')->on('subscribers')->onDelete('set null');

            $table->unsignedBigInteger('service_offer_id')->nullable();
            $table->foreign('service_offer_id')->references('id')->on('service_offers')->onDelete('set null');

            $table->enum('status', SubscriptionStatus::values())->default(SubscriptionStatus::ACTIVE->value);

            $table->timestamp('start_date');
            $table->timestamp('end_date');

            $table->string('canal');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
