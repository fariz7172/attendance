<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->enum('type', ['clock_in', 'clock_out', 'break_start', 'break_end']);
            $table->time('time');
            $table->decimal('face_match_score', 5, 4)->nullable();
            $table->string('photo_path')->nullable();
            $table->enum('status', ['on_time', 'late', 'early', 'absent'])->default('on_time');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['employee_id', 'date', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
