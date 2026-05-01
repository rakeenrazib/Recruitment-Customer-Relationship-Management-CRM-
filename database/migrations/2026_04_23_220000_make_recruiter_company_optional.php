<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF');

            Schema::create('recruiters_tmp', function (Blueprint $table) {
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

            DB::table('recruiters')->orderBy('id')->get()->each(function ($recruiter) {
                DB::table('recruiters_tmp')->insert([
                    'id' => $recruiter->id,
                    'user_id' => $recruiter->user_id,
                    'company_id' => $recruiter->company_id,
                    'full_name' => $recruiter->full_name,
                    'phone' => $recruiter->phone,
                    'location' => $recruiter->location ?? null,
                    'department' => $recruiter->department,
                    'title' => $recruiter->title,
                    'bio' => $recruiter->bio,
                    'verification_requested_at' => $recruiter->verification_requested_at,
                    'verified_at' => $recruiter->verified_at,
                    'created_at' => $recruiter->created_at,
                    'updated_at' => $recruiter->updated_at,
                ]);
            });

            Schema::drop('recruiters');
            Schema::rename('recruiters_tmp', 'recruiters');
            DB::statement('PRAGMA foreign_keys = ON');
        } else {
            if (! Schema::hasColumn('recruiters', 'location')) {
                Schema::table('recruiters', function (Blueprint $table) {
                    $table->string('location')->nullable()->after('phone');
                });
            }

            Schema::table('recruiters', function (Blueprint $table) {
                $table->foreignId('company_id')->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        Schema::table('recruiters', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable(false)->change();
        });

        if (Schema::hasColumn('recruiters', 'location')) {
            Schema::table('recruiters', function (Blueprint $table) {
                $table->dropColumn('location');
            });
        }
    }
};
