<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('translation_usages', function (Blueprint $table): void {
            $table->id();
            $table->string('period', 7);
            $table->string('provider', 32);
            $table->unsignedBigInteger('used_characters')->default(0);
            $table->unsignedBigInteger('monthly_limit')->default(500000);
            $table->timestamp('blocked_at')->nullable();
            $table->timestamps();

            $table->unique(['period', 'provider'], 'translation_usages_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('translation_usages');
    }
};

