<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prospects', function (Blueprint $table) {
            $table->id();

            // pemilik prospect (user login)
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // data utama prospect
            $table->string('name');
            $table->string('phone')->nullable()->unique();
            $table->text('address')->nullable();

            // sumber lead
            $table->string('source')->nullable();
            // contoh: WhatsApp, Instagram, Marketplace, Referral

            // minat customer
            $table->string('interest_of')->nullable();
            // contoh: Sepeda Listrik, Motor, Produk A

            // status pipeline (bebas string, tidak pakai enum)
            $table->string('status')->default('new');
            // contoh: new, contacted, negotiation, won, lost

            // metode pembayaran
            $table->string('payment_method')->nullable();
            // contoh: cash, credit, transfer, leasing

            // catatan tambahan
            $table->text('notes')->nullable();

            // jadwal follow up
            $table->timestamp('follow_up_at')->nullable();

            // tracking otomatis Laravel
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prospects');
    }
};