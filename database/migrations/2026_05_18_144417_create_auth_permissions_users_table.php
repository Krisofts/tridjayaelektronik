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
        Schema::create('auth_permissions_users', function (Blueprint $table) {
            $table->id();

            // relasi ke users
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // permission dari config
            $table->string('permission');

            $table->timestamps();

            // mencegah duplikasi
            $table->unique(['user_id', 'permission']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auth_permissions_users');
    }
};