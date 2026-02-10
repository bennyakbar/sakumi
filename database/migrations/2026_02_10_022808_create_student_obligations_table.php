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
        Schema::create('student_obligations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students');
            $table->foreignId('fee_type_id')->constrained('fee_types');
            $table->unsignedSmallInteger('month'); // 1-12
            $table->unsignedSmallInteger('year'); // e.g., 2026
            $table->decimal('amount', 15, 2);
            $table->boolean('is_paid')->default(false);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->timestamp('paid_at')->nullable();
            $table->foreignId('transaction_item_id')->nullable()->constrained('transaction_items');
            $table->timestamps();

            $table->unique(['student_id', 'fee_type_id', 'month', 'year']);
            $table->index(['student_id', 'is_paid']);
            $table->index(['year', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_obligations');
    }
};
