<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('borrow_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained('books')->cascadeOnDelete();
            $table->string('borrower_name')->nullable();  // display name of requester
            $table->string('full_name');
            $table->string('phone');
            $table->string('email');
            $table->text('message')->nullable();
            $table->date('borrow_date');
            $table->date('return_date');
            $table->enum('status', ['pending', 'approved', 'rejected', 'returned'])->default('pending');
            $table->boolean('read_by_owner')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('borrow_requests');
    }
};
