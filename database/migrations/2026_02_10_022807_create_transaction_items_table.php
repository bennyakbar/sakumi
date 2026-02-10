<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaction_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('transactions')->onDelete('cascade');
            $table->foreignId('fee_type_id')->constrained('fee_types');
            $table->string('description')->nullable(); // e.g., "SPP Januari 2026"
            $table->decimal('amount', 15, 2);
            $table->unsignedSmallInteger('month')->nullable(); // 1-12 (for monthly fees)
            $table->unsignedSmallInteger('year')->nullable(); // e.g., 2026
            $table->timestamps();

            $table->index('transaction_id');
            $table->index(['year', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_items');
    }
};
