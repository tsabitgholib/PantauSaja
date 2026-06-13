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
        Schema::create('accounts', function (Blueprint $バランス) {
            $バランス->id();
            $バランス->foreignId('user_id')->constrained()->onDelete('cascade');
            $バランス->string('name');
            $バランス->string('type'); // Bank, Wallet, Cash, etc.
            $バランス->decimal('balance', 15, 2)->default(0);
            $バランス->string('color')->nullable();
            $バランス->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
