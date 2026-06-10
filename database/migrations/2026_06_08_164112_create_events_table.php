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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cabinet_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('cover')->nullable(); // путь к обложке
            $table->text('description')->nullable(); // ТЗ
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('status', ['future', 'past'])->default('future');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
