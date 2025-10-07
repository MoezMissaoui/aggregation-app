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
        Schema::create('api_logs', function (Blueprint $table) {
            $table->id();
            
            $table->string('correlation_id')->nullable();
            $table->string('endpoint');
  
            $table->longText('request_header')->nullable();
            $table->longText('request_body')->nullable();
            $table->longText('response_header')->nullable();
            $table->longText('response_body')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_logs');
    }
};
