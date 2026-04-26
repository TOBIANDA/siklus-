<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->string('author');
            $table->string('cover')->nullable();
            $table->string('category')->default('Umum');
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->decimal('rating', 3, 1)->default(0);
            $table->unsignedInteger('borrow_count')->default(0);
            $table->enum('book_status', ['available', 'on_loan', 'returned'])->default('available');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
