<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reviewer_id')->constrained('users')->cascadeOnDelete();
            $table->morphs('reviewable'); // reviewable_type, reviewable_id (untuk book atau user) - includes index
            $table->tinyInteger('rating')->between(1, 5); // 1-5 stars
            $table->text('comment')->nullable();
            $table->enum('type', ['book_review', 'lender_review'])->default('book_review');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
