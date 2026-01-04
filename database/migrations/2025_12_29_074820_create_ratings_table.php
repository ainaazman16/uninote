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
        Schema::create('ratings', function (Blueprint $table) {
             $table->id();
                $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('provider_id')->constrained('users')->cascadeOnDelete();
                $table->tinyInteger('rating'); // 1–5
                $table->text('comment')->nullable();
                $table->timestamps();

                $table->unique(['student_id', 'provider_id']); 
                    });
     }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
