<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add book_status to track the catalog availability
        Schema::table('books', function (Blueprint $table) {
            $table->enum('book_status', ['available', 'on_loan', 'returned'])
                  ->default('available')
                  ->after('borrow_count');
        });

        // Add borrower_name so the lender knows who's requesting
        Schema::table('borrow_requests', function (Blueprint $table) {
            $table->string('borrower_name')->nullable()->after('book_id');
            $table->text('message')->nullable()->after('email');
            $table->boolean('read_by_owner')->default(false)->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn('book_status');
        });
        Schema::table('borrow_requests', function (Blueprint $table) {
            $table->dropColumn(['borrower_name', 'message', 'read_by_owner']);
        });
    }
};
