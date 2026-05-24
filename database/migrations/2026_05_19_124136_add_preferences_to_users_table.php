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
        Schema::table('users', function (Blueprint $table) {
            // Language & appearance
            $table->string('language_preference')->default('id')->after('rating');
            $table->string('theme_preference')->default('light')->after('language_preference');
            $table->string('text_size')->default('normal')->after('theme_preference');

            // Notification preferences
            $table->boolean('notif_borrow')->default(true)->after('text_size');
            $table->boolean('notif_message')->default(true)->after('notif_borrow');
            $table->boolean('notif_return')->default(true)->after('notif_message');
            $table->boolean('notif_updates')->default(false)->after('notif_return');

            // Privacy preferences
            $table->boolean('public_profile')->default(true)->after('notif_updates');
            $table->boolean('show_location')->default(true)->after('public_profile');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'language_preference', 'theme_preference', 'text_size',
                'notif_borrow', 'notif_message', 'notif_return', 'notif_updates',
                'public_profile', 'show_location',
            ]);
        });
    }
};
