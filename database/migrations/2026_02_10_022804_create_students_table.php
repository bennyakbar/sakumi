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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('nis', 20)->unique();
            $table->string('nisn', 20)->unique()->nullable();
            $table->string('name');
            $table->foreignId('class_id')->constrained('classes');
            $table->foreignId('category_id')->constrained('student_categories');
            $table->char('gender', 1); // L/P
            $table->date('birth_date')->nullable();
            $table->string('birth_place', 100)->nullable();
            $table->string('parent_name')->nullable();
            $table->string('parent_phone', 20)->nullable();
            $table->string('parent_whatsapp', 20)->nullable();
            $table->text('address')->nullable();
            $table->string('status', 20)->default('active'); // active, graduated, dropout, transferred
            $table->date('enrollment_date')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
