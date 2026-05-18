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

            // tambah kolom active
            $table->boolean('active')->default(true)->after('email');

            // hapus kolom status (kalau ada)
            if (Schema::hasColumn('users', 'status')) {
                $table->dropColumn('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // rollback: hapus active
            $table->dropColumn('active');

            // kembalikan status
            $table->string('status')->nullable();
        });
    }
};