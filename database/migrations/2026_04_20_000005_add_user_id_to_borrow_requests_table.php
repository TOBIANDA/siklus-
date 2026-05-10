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
        Schema::table('borrow_requests', function (Blueprint $table) {
            // Add user_id column for the borrower
            $table->foreignId('user_id')->nullable()->after('book_id')->constrained('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('borrow_requests', function (Blueprint $table) {
            $table->dropForeignKeyIfExists(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
