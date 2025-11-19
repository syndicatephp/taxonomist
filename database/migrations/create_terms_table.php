<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('terms', function (Blueprint $table) {
            // === Identifiers ===
            $table->id();
            $table->string('slug');

            // === UI Label  ===
            $table->string('name');

            // === Taxonomy Association ===
            $table->string('taxonomy_name');

            // === If not null, this row is 'owned' by code ===
            $table->string('taxonomy_fqn')->nullable();

            // === Data ===
            $table->json('meta')->nullable();

            // === Hierarchy ===
            $table->foreignId('parent_id')->nullable()->constrained('terms')->nullOnDelete();

            // === Sorting ===
            $table->unsignedInteger('order_column')->nullable()->index();

            // === Constraints ===
            $table->unique(['taxonomy_name', 'slug']);

            // === Timestamps ===
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('terms');
    }
};
