<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('profile_photo_path')->nullable()->after('password');
            $table->string('headline')->nullable()->after('profile_photo_path');
        });

        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('company_name');
            $table->string('industry')->nullable();
            $table->string('website');
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->timestamps();
        });

        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('full_name');
            $table->string('phone')->nullable();
            $table->string('location')->nullable();
            $table->text('bio')->nullable();
            $table->text('portfolio')->nullable();
            $table->text('details')->nullable();
            $table->string('resume_link')->nullable();
            $table->timestamps();
        });

        Schema::create('recruiters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();
            $table->string('full_name');
            $table->string('phone')->nullable();
            $table->string('location')->nullable();
            $table->string('department')->nullable();
            $table->string('title')->nullable();
            $table->text('bio')->nullable();
            $table->timestamp('verification_requested_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });

        Schema::create('recruiter_verification_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recruiter_id')->constrained()->cascadeOnDelete();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('pending');
            $table->text('message')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('company_follows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->constrained()->cascadeOnDelete();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['candidate_id', 'company_id']);
        });

        Schema::create('interview_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained()->cascadeOnDelete();
            $table->string('plan_type')->default('standard');
            $table->string('evaluation_strategy')->default('scoring_rubric');
            $table->json('stages');
            $table->timestamps();
        });

        Schema::table('jobs', function (Blueprint $table) {
            $table->foreignId('recruiter_id')->nullable()->after('user_id')->constrained('recruiters')->nullOnDelete();
            $table->foreignId('company_id')->nullable()->after('recruiter_id')->constrained()->nullOnDelete();
            $table->text('requirements')->nullable()->after('description');
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->foreignId('candidate_id')->nullable()->after('user_id')->constrained('candidates')->nullOnDelete();
            $table->timestamp('status_updated_at')->nullable()->after('status');
            $table->string('evaluation_method')->nullable()->after('notes');
            $table->decimal('evaluation_score', 5, 2)->nullable()->after('evaluation_method');
            $table->text('evaluation_summary')->nullable()->after('evaluation_score');
        });

        Schema::table('app_notifications', function (Blueprint $table) {
            $table->string('subject_type')->nullable()->after('message');
            $table->unsignedBigInteger('subject_id')->nullable()->after('subject_type');
        });
    }

    public function down(): void
    {
        Schema::table('app_notifications', function (Blueprint $table) {
            $table->dropColumn(['subject_type', 'subject_id']);
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->dropConstrainedForeignId('candidate_id');
            $table->dropColumn(['status_updated_at', 'evaluation_method', 'evaluation_score', 'evaluation_summary']);
        });

        Schema::table('jobs', function (Blueprint $table) {
            $table->dropConstrainedForeignId('recruiter_id');
            $table->dropConstrainedForeignId('company_id');
            $table->dropColumn('requirements');
        });

        Schema::dropIfExists('interview_plans');
        Schema::dropIfExists('company_follows');
        Schema::dropIfExists('recruiter_verification_requests');
        Schema::dropIfExists('recruiters');
        Schema::dropIfExists('candidates');
        Schema::dropIfExists('companies');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['profile_photo_path', 'headline']);
        });
    }
};
