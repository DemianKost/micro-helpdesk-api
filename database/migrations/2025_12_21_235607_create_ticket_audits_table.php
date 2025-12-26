<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_audits', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('ticket_id')
                ->index()
                ->constrained('tickets')
                ->cascadeOnDelete();
            $table->foreignUuid('actor_id')
                ->index()
                ->constrained('users')
                ->cascadeOnDelete();
            
            $table->string('action');

            $table->string('from_status')->nullable();
            $table->string('to_status')->nullable();

            $table->json('meta')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_audits');
    }
};
