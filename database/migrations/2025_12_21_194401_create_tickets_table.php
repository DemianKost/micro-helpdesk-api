<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Src\Domains\Ticket\Enums\TicketStatus;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('requester_id')
                ->index()
                ->constrained('users')
                ->cascadeOnDelete();
            $table->foreignUuid('assignee_id')
                ->nullable()
                ->index()
                ->constrained('users')
                ->cascadeOnDelete();
            
            $table->string('title');
            $table->text('description');
            $table->string('priority')->nullable();
            $table->string('status')
                ->index()
                ->default(TicketStatus::OPEN);
            $table->date('resolved_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
