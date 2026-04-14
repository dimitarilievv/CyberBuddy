<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('total_points')->default(0)->after('email');
            $table->integer('current_streak')->default(0)->after('total_points');
            $table->integer('ai_interactions')->default(0)->after('current_streak');
            $table->timestamp('last_activity_at')->nullable()->after('ai_interactions');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['total_points', 'current_streak', 'ai_interactions', 'last_activity_at']);
        });
    }
};
