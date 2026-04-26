<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\User;

class BooksSeeder extends Seeder
{
    public function run(): void
    {
        $adidharma = User::where('name', 'Adidharma')->first()->id ?? 1;
        $harun = User::where('name', 'Bang Harun')->first()->id ?? 1;
        $tobi = User::where('name', 'Tobi')->first()->id ?? 1;

        $books = [
            [
                'title'        => 'The Little Prince',
                'author'       => 'Antoine de Saint-Exupéry',
                'cover'        => 'https://m.media-amazon.com/images/I/71OZY035QKL._AC_UF1000,1000_QL80_.jpg',
                'category'     => 'Fiksi',
                'description'  => 'Sebuah novela filosofis yang menceritakan seorang pangeran kecil yang bepergian dari planet ke planet. Melalui perjalanan itu ia mempelajari cinta, kesepian, dan makna hidup dari sudut pandang anak-anak yang jernih.',
                'user_id'      => $adidharma,
                'location'     => 'Malang, Dinoyo',
                'rating'       => 4.8,
                'borrow_count' => 24,
            ],
            [
                'title'        => 'The Art of Loving',
                'author'       => 'Erich Fromm',
                'cover'        => 'https://m.media-amazon.com/images/I/71rNyXLHvaL._AC_UF1000,1000_QL80_.jpg',
                'category'     => 'Non-Fiksi',
                'description'  => 'Buku psikologi dan filsafat klasik yang menelaah cinta bukan sebagai perasaan pasif, melainkan keterampilan aktif yang harus dipelajari. Fromm berargumen bahwa sebagian besar orang salah memahami cinta.',
                'user_id'      => $harun,
                'location'     => 'Malang, Soekarno-Hatta',
                'rating'       => 4.6,
                'borrow_count' => 18,
            ],
            [
                'title'        => 'Bumi Manusia',
                'author'       => 'Pramoedya Ananta Toer',
                'cover'        => 'https://m.media-amazon.com/images/I/91HG0bMBkJL._AC_UF1000,1000_QL80_.jpg',
                'category'     => 'Fiksi',
                'description'  => 'Novel pertama dari Tetralogi Buru. Berkisah tentang Minke, pemuda pribumi di era kolonial Belanda yang jatuh cinta pada Annelies. Sebuah potret perjuangan martabat manusia melawan penindasan.',
                'user_id'      => $tobi,
                'location'     => 'Malang, Lowokwaru',
                'rating'       => 4.9,
                'borrow_count' => 31,
            ],
            [
                'title'        => 'Atomic Habits',
                'author'       => 'James Clear',
                'cover'        => 'https://m.media-amazon.com/images/I/81YkqyaFVEL._AC_UF1000,1000_QL80_.jpg',
                'category'     => 'Non-Fiksi',
                'description'  => 'Panduan praktis membangun kebiasaan baik dan meninggalkan kebiasaan buruk. James Clear memperkenalkan sistem 1% perbaikan harian yang terbukti efektif secara ilmiah.',
                'user_id'      => $adidharma,
                'location'     => 'Malang, Dinoyo',
                'rating'       => 4.7,
                'borrow_count' => 27,
            ],
            [
                'title'        => 'Laskar Pelangi',
                'author'       => 'Andrea Hirata',
                'cover'        => 'https://m.media-amazon.com/images/I/81eB+70QKXL._AC_UF1000,1000_QL80_.jpg',
                'category'     => 'Fiksi',
                'description'  => 'Novel inspiratif tentang sepuluh anak dari keluarga miskin di Belitung yang berjuang mendapatkan pendidikan. Kisah persahabatan, mimpi, dan semangat pantang menyerah.',
                'user_id'      => $harun,
                'location'     => 'Malang, Ketawanggede',
                'rating'       => 4.8,
                'borrow_count' => 22,
            ],
            [
                'title'        => 'Sapiens: A Brief History of Humankind',
                'author'       => 'Yuval Noah Harari',
                'cover'        => 'https://m.media-amazon.com/images/I/713jIoMO3UL._AC_UF1000,1000_QL80_.jpg',
                'category'     => 'Non-Fiksi',
                'description'  => 'Harari mengajak pembaca menilik 70.000 tahun sejarah manusia mulai dari Homo sapiens Afrika hingga dunia modern. Buku yang mengubah cara pandang tentang peradaban.',
                'user_id'      => $tobi,
                'location'     => 'Malang, Lowokwaru',
                'rating'       => 4.7,
                'borrow_count' => 19,
            ],
            [
                'title'        => 'Negeri 5 Menara',
                'author'       => 'Ahmad Fuadi',
                'cover'        => 'https://m.media-amazon.com/images/I/81hf8+V9g4L._AC_UF1000,1000_QL80_.jpg',
                'category'     => 'Fiksi',
                'description'  => 'Kisah Alif dan lima sahabatnya di Pondok Madani yang belajar bahwa man jadda wajada — siapa yang bersungguh-sungguh pasti berhasil. Novel semi-otobiografi dengan latar pesantren.',
                'user_id'      => $adidharma,
                'location'     => 'Malang, Dinoyo',
                'rating'       => 4.6,
                'borrow_count' => 15,
            ],
            [
                'title'        => 'Rich Dad Poor Dad',
                'author'       => 'Robert T. Kiyosaki',
                'cover'        => 'https://m.media-amazon.com/images/I/81BE7eeKzAL._AC_UF1000,1000_QL80_.jpg',
                'category'     => 'Non-Fiksi',
                'description'  => 'Pelajaran keuangan pribadi dari dua sosok "ayah" yang berbeda. Kiyosaki mengajarkan pentingnya melek finansial, aset vs liabilitas, dan cara berpikir orang kaya.',
                'user_id'      => $harun,
                'location'     => 'Malang, Soekarno-Hatta',
                'rating'       => 4.5,
                'borrow_count' => 20,
            ],
            [
                'title'        => 'Perahu Kertas',
                'author'       => 'Dee Lestari',
                'cover'        => 'https://m.media-amazon.com/images/I/71LrJfnGLDL._AC_UF1000,1000_QL80_.jpg',
                'category'     => 'Fiksi',
                'description'  => 'Kisah cinta Kugy dan Keenan yang bertemu di Bandung dan menjalani perjalanan hidup yang saling bersilangan. Novel yang penuh puisi dan impian.',
                'user_id'      => $tobi,
                'location'     => 'Malang, Lowokwaru',
                'rating'       => 4.5,
                'borrow_count' => 14,
            ],
            [
                'title'        => 'Filosofi Teras',
                'author'       => 'Henry Manampiring',
                'cover'        => 'https://m.media-amazon.com/images/I/71l+N4GzM4L._AC_UF1000,1000_QL80_.jpg',
                'category'     => 'Non-Fiksi',
                'description'  => 'Pengantar Stoicisme modern yang disesuaikan dengan budaya Indonesia. Buku ini mengajarkan cara menghadapi kecemasan, amarah, dan ketidakpastian hidup dengan tenang dan bijak.',
                'user_id'      => $adidharma,
                'location'     => 'Malang, Dinoyo',
                'rating'       => 4.6,
                'borrow_count' => 17,
            ],
            [
                'title'        => 'The Psychology of Money',
                'author'       => 'Morgan Housel',
                'cover'        => 'https://m.media-amazon.com/images/I/71g2ednj0JL._AC_UF1000,1000_QL80_.jpg',
                'category'     => 'Non-Fiksi',
                'description'  => 'Pelajaran berharga tentang kekayaan, ketamakan, dan kebahagiaan.',
                'user_id'      => User::where('email', 'kuahsayurgamingyo@gmail.com')->first()->id ?? 1,
                'location'     => 'Malang, Kedungkandang',
                'rating'       => 4.9,
                'borrow_count' => 5,
            ]
        ];

        foreach ($books as $bookData) {
            Book::create($bookData);
        }
    }
}
