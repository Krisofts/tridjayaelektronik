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
        Schema::create('auth_groups_users', function (Blueprint $table) {
            $table->id();

            // relasi ke users
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // group dari config (admin, staff, sales, dll)
            $table->string('group');

            // timestamps Laravel (created_at & updated_at)
            $table->timestamps();

            // mencegah user dobel di group yang sama
            $table->unique(['user_id', 'group']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auth_groups_users');
    }
};