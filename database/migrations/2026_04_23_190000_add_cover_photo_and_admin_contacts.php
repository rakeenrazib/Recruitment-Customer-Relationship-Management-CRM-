<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('cover_photo_path')->nullable()->after('profile_photo_path');
        });

        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('support_email');
            $table->string('support_phone');
            $table->timestamps();
        });

        DB::table('admins')->insert([
            'support_email' => 'support@talenthub.test',
            'support_phone' => '+880 1000-000000',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('admins');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('cover_photo_path');
        });
    }
};
