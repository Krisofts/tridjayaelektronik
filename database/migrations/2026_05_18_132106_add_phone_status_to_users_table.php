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
        Schema::table('users', function (Blueprint $table) {

            // tambah kolom
            $table->string('phone')->nullable()->after('email');
            $table->boolean('status')->default(true)->after('phone');

            // index untuk performa
            $table->index('phone');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // hapus index dulu
            $table->dropIndex(['phone']);
            $table->dropIndex(['status']);

            // hapus kolom
            $table->dropColumn(['phone', 'status']);
        });
    }
};