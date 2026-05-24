<?php

/**
 * Helper file untuk testing translations
 */

// Test semua translation files sudah terbuat
$langs = ['en', 'id'];
$files = ['auth', 'common', 'navigation', 'home', 'settings', 'profile', 'search', 'borrow', 'lent', 'messages', 'book', 'validation'];

echo "Checking translation files...\n\n";

foreach ($langs as $lang) {
    echo "Language: $lang\n";
    foreach ($files as $file) {
        $path = "resources/lang/$lang/$file.php";
        if (file_exists($path)) {
            echo "  ✓ $file.php\n";
        } else {
            echo "  ✗ $file.php (MISSING)\n";
        }
    }
    echo "\n";
}

echo "To use translations in views, use __('file.key');\n";
echo "Example: {{ __('home.popular_books') }}\n";
