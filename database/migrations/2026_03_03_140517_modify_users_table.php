<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['child', 'parent', 'teacher', 'admin'])->default('child')->after('email');
            $table->date('date_of_birth')->nullable()->after('role');
            $table->foreignId('parent_id')->nullable()->constrained('users')->nullOnDelete()->after('date_of_birth');
            $table->boolean('is_active')->default(true)->after('parent_id');
            $table->timestamp('last_login_at')->nullable()->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['role', 'date_of_birth', 'parent_id', 'is_active', 'last_login_at']);
        });
    }
};
