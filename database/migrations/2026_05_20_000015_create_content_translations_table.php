<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_translations', function (Blueprint $table): void {
            $table->id();
            $table->string('translatable_type');
            $table->unsignedBigInteger('translatable_id');
            $table->string('locale', 8);
            $table->string('field', 64);
            $table->longText('value')->nullable();
            $table->string('source_hash', 64)->nullable();
            $table->string('provider', 32)->nullable();
            $table->timestamp('translated_at')->nullable();
            $table->timestamps();

            $table->unique(['translatable_type', 'translatable_id', 'locale', 'field'], 'content_translations_unique');
            $table->index(['locale', 'field']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_translations');
    }
};

