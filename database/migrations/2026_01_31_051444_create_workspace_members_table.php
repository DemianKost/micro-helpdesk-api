<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Src\Domains\Workspaces\Enums\WorkspaceRole;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workspace_members', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('workspace_id')
                ->index()
                ->constrained('workspaces')
                ->cascadeOnDelete();
            $table->foreignUuid('user_id')
                ->index()
                ->constrained('users')
                ->cascadeOnDelete();
                
            $table->string('role')->default(WorkspaceRole::OWNER->value);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workspace_members');
    }
};
