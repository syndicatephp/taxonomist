<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Syndicate\Taxonomist\Models\Term;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('termables', function (Blueprint $table) {
            // Pivot Connection
            $table->foreignIdFor(Term::class)->index()->constrained()->cascadeOnDelete();
            $table->morphs('model');

            // Sorting
            $table->unsignedInteger('order_column')->nullable()->index();

            // Created, Updated, Deleted
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('termables');
    }
};
