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
        Schema::create('api_keys', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Descriptive name for the API key');
            $table->string('key_hash')->comment('Hashed API key for security');
            $table->string('key_prefix', 8)->comment('First 8 characters of the key for identification');
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            $table->text('description')->nullable()->comment('Description of the API key purpose');
            $table->json('permissions')->nullable()->comment('JSON array of permissions/scopes');
            $table->timestamp('last_used_at')->nullable()->comment('Last time this API key was used');
            $table->timestamp('expires_at')->nullable()->comment('Expiration date of the API key');
            $table->boolean('is_active')->default(true)->comment('Whether the API key is active');
            $table->timestamps();

            // Indexes for performance
            $table->index(['service_id', 'is_active']);
            $table->index('key_prefix');
            $table->index('expires_at');
            $table->index('last_used_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_keys');
    }
};