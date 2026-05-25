<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\User;

class BooksSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus semua data buku lama agar tidak duplikat
        \DB::table('borrow_requests')->delete();
        \DB::table('books')->delete();

        $adidharma = User::where('email', 'adidharma@gmail.com')->first()->id ?? 1;
        $harun     = User::where('email', 'bangharun@gmail.com')->first()->id ?? 1;
        $tobi      = User::where('email', 'tobi@gmail.com')->first()->id ?? 1;
        $bro       = User::where('email', 'kuahsayurgamingyo@gmail.com')->first()->id ?? 1;

        $books = [
            [
                'title'        => 'The Little Prince',
                'author'       => 'Antoine de Saint-Exupéry',
                'cover'        => 'cover_the_little_prince.webp',
                'category'     => 'Fiksi',
                'description'  => 'Sebuah novela filosofis yang menceritakan seorang pangeran kecil yang bepergian dari planet ke planet.',
                'user_id'      => $adidharma,
                'location'     => 'Malang, Dinoyo',
                'rating'       => 4.8,
                'borrow_count' => 24,
            ],
            [
                'title'        => 'The Art of Loving',
                'author'       => 'Erich Fromm',
                'cover'        => 'cover_the_art_of_loving.webp',
                'category'     => 'Non-Fiksi',
                'description'  => 'Buku psikologi dan filsafat klasik yang menelaah cinta bukan sebagai perasaan pasif, melainkan keterampilan aktif.',
                'user_id'      => $harun,
                'location'     => 'Jakarta Selatan, Kebayoran',
                'rating'       => 4.6,
                'borrow_count' => 18,
            ],
            [
                'title'        => 'Bumi Manusia',
                'author'       => 'Pramoedya Ananta Toer',
                'cover'        => 'cover_bumi_manusia.webp',
                'category'     => 'Fiksi',
                'description'  => 'Novel pertama dari Tetralogi Buru. Berkisah tentang Minke, pemuda pribumi di era kolonial Belanda.',
                'user_id'      => $tobi,
                'location'     => 'Bandung, Dago',
                'rating'       => 4.9,
                'borrow_count' => 31,
            ],
            [
                'title'        => 'Atomic Habits',
                'author'       => 'James Clear',
                'cover'        => 'cover_atomic_habits.webp',
                'category'     => 'Non-Fiksi',
                'description'  => 'Panduan praktis membangun kebiasaan baik dan meninggalkan kebiasaan buruk.',
                'user_id'      => $adidharma,
                'location'     => 'Malang, Dinoyo',
                'rating'       => 4.7,
                'borrow_count' => 27,
            ],
            [
                'title'        => 'The Art of Loving',
                'author'       => 'Erich Fromm',
                'cover'        => 'cover_art_of_loving.webp',
                'category'     => 'Non-Fiksi',
                'description'  => 'Buku psikologi dan filsafat klasik yang menelaah cinta bukan sebagai perasaan pasif, melainkan keterampilan aktif yang bisa dipelajari.',
                'user_id'      => $harun,
                'location'     => 'Jakarta Selatan, Kebayoran',
                'rating'       => 4.6,
                'borrow_count' => 18,
            ],
            [
                'title'        => 'Sapiens: A Brief History of Humankind',
                'author'       => 'Yuval Noah Harari',
                'cover'        => 'cover_sapiens.webp',
                'category'     => 'Non-Fiksi',
                'description'  => 'Harari mengajak pembaca menilik 70.000 tahun sejarah manusia.',
                'user_id'      => $tobi,
                'location'     => 'Bandung, Dago',
                'rating'       => 4.7,
                'borrow_count' => 19,
            ],
            [
                'title'        => 'Negeri 5 Menara',
                'author'       => 'Ahmad Fuadi',
                'cover'        => 'cover_negeri_5_menara.webp',
                'category'     => 'Fiksi',
                'description'  => 'Kisah Alif dan lima sahabatnya di Pondok Madani yang belajar bahwa man jadda wajada.',
                'user_id'      => $adidharma,
                'location'     => 'Malang, Dinoyo',
                'rating'       => 4.6,
                'borrow_count' => 15,
            ],
            [
                'title'        => 'Rich Dad Poor Dad',
                'author'       => 'Robert T. Kiyosaki',
                'cover'        => 'cover_rich_dad_and_poor_dad.webp',
                'category'     => 'Non-Fiksi',
                'description'  => 'Pelajaran keuangan pribadi dari dua sosok "ayah" yang berbeda.',
                'user_id'      => $harun,
                'location'     => 'Jakarta Selatan, Kebayoran',
                'rating'       => 4.5,
                'borrow_count' => 20,
            ],
            [
                'title'        => 'Perahu Kertas',
                'author'       => 'Dee Lestari',
                'cover'        => 'cover_perahu_kertas.webp',
                'category'     => 'Fiksi',
                'description'  => 'Kisah cinta Kugy dan Keenan yang bertemu di Bandung dan menjalani perjalanan hidup yang saling bersilangan.',
                'user_id'      => $tobi,
                'location'     => 'Bandung, Dago',
                'rating'       => 4.5,
                'borrow_count' => 14,
            ],
            [
                'title'        => 'Filosofi Teras',
                'author'       => 'Henry Manampiring',
                'cover'        => 'cover_filosofi_teras.webp',
                'category'     => 'Non-Fiksi',
                'description'  => 'Pengantar Stoicisme modern yang disesuaikan dengan budaya Indonesia.',
                'user_id'      => $adidharma,
                'location'     => 'Malang, Dinoyo',
                'rating'       => 4.6,
                'borrow_count' => 17,
            ],
            [
                'title'        => 'The Psychology of Money',
                'author'       => 'Morgan Housel',
                'cover'        => 'cover_psychology_of_money.webp',
                'category'     => 'Non-Fiksi',
                'description'  => 'Pelajaran berharga tentang kekayaan, ketamakan, dan kebahagiaan.',
                'user_id'      => $bro,
                'location'     => 'Malang, Kedungkandang',
                'rating'       => 4.9,
                'borrow_count' => 5,
            ],
        ];

        foreach ($books as $bookData) {
            Book::create($bookData);
        }
    }
}
