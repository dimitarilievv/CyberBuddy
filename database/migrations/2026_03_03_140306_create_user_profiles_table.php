<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('school')->nullable();
            $table->string('grade')->nullable();
            $table->string('language')->default('mk');
            $table->json('interests')->nullable();
            $table->boolean('is_colorblind')->default(false);
            $table->boolean('large_font')->default(false);
            $table->boolean('dark_mode')->default(false);
            $table->string('avatar')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
