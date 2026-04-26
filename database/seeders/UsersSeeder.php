<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Adidharma',
                'email' => 'adidharma@gmail.com',
                'password' => Hash::make('123456'),
                'avatar' => 'avatar_adidharma.jpg',
                'bio' => 'Passionate archivist of Indonesian literature and political philosophy.',
                'level' => 'Academic Level 4',
                'province' => 'Jawa Timur',
                'city' => 'Malang',
            ],
            [
                'name' => 'Bang Harun',
                'email' => 'harun@gmail.com',
                'password' => Hash::make('123456'),
                'avatar' => 'avatar_user.png',
                'bio' => 'Lover of Non-Fiction and history.',
                'level' => 'Scholar Level 2',
                'province' => 'DKI Jakarta',
                'city' => 'Jakarta Selatan',
            ],
            [
                'name' => 'Tobi',
                'email' => 'tobi@gmail.com',
                'password' => Hash::make('123456'),
                'avatar' => 'avatar_tobby.jpg',
                'bio' => 'Fiksi dan sastra klasik.',
                'level' => 'Reader Level 3',
                'province' => 'Jawa Barat',
                'city' => 'Bandung',
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@gmail.com',
                'password' => Hash::make('123456'),
                'avatar' => 'avatar_user.png',
                'bio' => 'Novel & Komik addict.',
                'level' => 'Reader Level 2',
                'province' => 'Jawa Tengah',
                'city' => 'Semarang',
            ],
            [
                'name' => 'Siti Aminah',
                'email' => 'siti@gmail.com',
                'password' => Hash::make('123456'),
                'avatar' => 'avatar_user.png',
                'bio' => 'Suka buku masak dan gardening.',
                'level' => 'Newbie',
                'province' => 'DI Yogyakarta',
                'city' => 'Sleman',
            ],
            [
                'name' => 'Joko',
                'email' => 'joko@gmail.com',
                'password' => Hash::make('123456'),
                'avatar' => 'avatar_user.png',
                'bio' => 'Buku IT dan Programming.',
                'level' => 'Scholar Level 1',
                'province' => 'Banten',
                'city' => 'Tangerang',
            ],
            [
                'name' => 'Andrani',
                'email' => 'andrani@gmail.com',
                'password' => Hash::make('123456'),
                'avatar' => 'avatar_user.png',
                'bio' => 'I love reading self-improvement books.',
                'level' => 'Newbie',
                'province' => 'Jawa Timur',
                'city' => 'Surabaya',
            ],
            [
                'name' => 'Kuahs',
                'email' => 'kuahsayurgamingyo@gmail.com',
                'password' => Hash::make('123456'),
                'avatar' => 'avatar_user.png',
                'bio' => 'Pengguna aktif Siklus. Suka berbagi buku dan menemukan bacaan baru.',
                'level' => 'Reader Level 1',
                'province' => 'Jawa Timur',
                'city' => 'Malang',
            ],
        ];

        foreach ($users as $user) {
            User::firstOrCreate(['email' => $user['email']], $user);
        }
    }
}
