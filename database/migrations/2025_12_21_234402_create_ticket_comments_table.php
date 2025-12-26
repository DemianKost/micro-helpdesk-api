<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_comments', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('ticket_id')
                ->index()
                ->constrained('tickets')
                ->cascadeOnDelete();
            $table->foreignUuid('user_id')
                ->index()
                ->constrained('users')
                ->cascadeOnDelete();
            
            $table->text('body');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_comments');
    }
};
