<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Syndicate\Taxonomist\Models\Term;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('termables', function (Blueprint $table) {
            // === Term ===
            $table->foreignIdFor(Term::class)->index()->constrained()->cascadeOnDelete();

            // === Model ===
            $table->morphs('model');

            // === Sorting ===
            $table->unsignedInteger('order_column')->nullable()->index();

            // === Constraints ===
            $table->unique(['term_id', 'model_type', 'model_id']);

            // === Overriding Term ===
            $table->json('override')->nullable();
            $table->json('meta')->nullable();

            // === Timestamps ===
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('termables');
    }
};
