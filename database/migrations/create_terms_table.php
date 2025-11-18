<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('terms', function (Blueprint $table) {
            // === Identifier ===
            $table->id();

            // === Enum+Case matching ===
            $table->string('case');
            $table->string('taxonomy');
            $table->string('fqn');

            // === UI Label  ===
            $table->string('name')->nullable();

            // === Hierarchy ===
            $table->foreignId('parent_id')->nullable()->constrained('terms')->nullOnDelete();

            // === Constraints ===
            $table->unique(['taxonomy', 'case']);

            // === Timestamps ===
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('terms');
    }
};
