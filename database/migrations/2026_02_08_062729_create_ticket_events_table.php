<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_events', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('ticket_id')
                ->index()
                ->constrained('tickets')
                ->cascadeOnDelete();
            
            $table->unsignedInteger('seq')->unique();
            $table->string('type');
            $table->json('data')->nullable();
            $table->json('meta');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_events');
    }
};
