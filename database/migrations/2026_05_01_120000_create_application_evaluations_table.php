<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('application_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->cascadeOnDelete();
            $table->foreignId('recruiter_id')->constrained('recruiters')->cascadeOnDelete();
            $table->string('assessment_type');
            $table->decimal('general_score', 4, 2)->nullable();
            $table->decimal('final_score', 4, 2)->nullable();
            $table->text('comments')->nullable();
            $table->text('strengths')->nullable();
            $table->text('weaknesses')->nullable();
            $table->text('recommendation')->nullable();
            $table->json('rubrics')->nullable();
            $table->timestamps();

            $table->unique(['application_id', 'assessment_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('application_evaluations');
    }
};
