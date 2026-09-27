<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Field ini menyimpan tampilan kartu, engagement, kategori, dan pinned state.
        Schema::table('cards', function (Blueprint $table) {
            $table->string('background', 30)->default('paper')->after('tone');
            $table->string('font_style', 30)->default('hand')->after('background');
            $table->string('sticker', 30)->nullable()->after('font_style');
            $table->string('category', 40)->default('Rahasia')->after('sticker');
            $table->unsignedInteger('likes_count')->default(0)->after('category');
            $table->boolean('is_pinned')->default(false)->after('likes_count');
        });

        // Komentar dapat anonim sehingga user_id bersifat nullable.
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('card_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('message', 500);
            $table->boolean('is_approved')->default(true);
            $table->timestamps();
        });

        // Report menjadi antrean kerja admin untuk ditinjau.
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('card_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('reason', 120);
            $table->string('status', 30)->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
        Schema::dropIfExists('comments');
        Schema::table('cards', function (Blueprint $table) {
            $table->dropColumn(['background', 'font_style', 'sticker', 'category', 'likes_count', 'is_pinned']);
        });
    }
};
