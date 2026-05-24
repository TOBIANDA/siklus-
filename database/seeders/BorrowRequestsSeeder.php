<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Book;
use App\Models\BorrowRequest;

class BorrowRequestsSeeder extends Seeder
{
    public function run(): void
    {
        $andrani = User::where('email', 'andrani@gmail.com')->first();
        $tobi    = User::where('email', 'tobi@gmail.com')->first();
        $budi    = User::where('email', 'budisantoso@gmail.com')->first();
        $siti    = User::where('email', 'sitiaminah@gmail.com')->first();
        $joko    = User::where('email', 'joko@gmail.com')->first();
        $bro     = User::where('email', 'kuahsayurgamingyo@gmail.com')->first();

        $adidharma = User::where('email', 'adidharma@gmail.com')->first();
        $harun     = User::where('email', 'bangharun@gmail.com')->first();

        if (!$adidharma || !$harun) return;

        $adidharmaBooks = Book::where('user_id', $adidharma->id)->get();
        $harunBooks     = Book::where('user_id', $harun->id)->get();

        $b1 = $adidharmaBooks->get(0); // The Little Prince
        $b2 = $adidharmaBooks->get(1); // Atomic Habits
        $b3 = $adidharmaBooks->get(2); // Negeri 5 Menara
        $b4 = $harunBooks->get(0);     // The Art of Loving
        $b5 = $harunBooks->get(1);     // Laskar Pelangi

        // ---- Requests ke buku Adidharma ----
        if ($andrani && $b1) {
            BorrowRequest::create([
                'book_id'       => $b1->id,
                'user_id'       => $andrani->id,
                'borrower_name' => $andrani->name,
                'full_name'     => $andrani->name,
                'phone'         => '081234567890',
                'email'         => $andrani->email,
                'message'       => 'Halo kak, boleh pinjam? Saya sudah lama cari buku ini.',
                'borrow_date'   => now()->addDays(1),
                'return_date'   => now()->addDays(8),
                'status'        => 'pending',
                'read_by_owner' => false,
            ]);
        }

        if ($budi && $b2) {
            BorrowRequest::create([
                'book_id'       => $b2->id,
                'user_id'       => $budi->id,
                'borrower_name' => $budi->name,
                'full_name'     => $budi->name,
                'phone'         => '082233445566',
                'email'         => $budi->email,
                'message'       => 'Wah bagus nih, ngantri ya kalau sudah selesai!',
                'borrow_date'   => now()->addDays(10),
                'return_date'   => now()->addDays(17),
                'status'        => 'pending',
                'read_by_owner' => false,
            ]);
        }

        if ($siti && $b3) {
            BorrowRequest::create([
                'book_id'       => $b3->id,
                'user_id'       => $siti->id,
                'borrower_name' => $siti->name,
                'full_name'     => $siti->name,
                'phone'         => '085511223344',
                'email'         => $siti->email,
                'message'       => 'Boleh pinjam untuk keponakan saya tidak?',
                'borrow_date'   => now()->addDays(2),
                'return_date'   => now()->addDays(5),
                'status'        => 'rejected',
                'read_by_owner' => true,
            ]);
        }

        if ($joko && $b1) {
            BorrowRequest::create([
                'book_id'       => $b1->id,
                'user_id'       => $joko->id,
                'borrower_name' => $joko->name,
                'full_name'     => $joko->name,
                'phone'         => '089977665544',
                'email'         => $joko->email,
                'message'       => 'Buku ini rilis tahun berapa kak?',
                'borrow_date'   => now()->addDays(3),
                'return_date'   => now()->addDays(10),
                'status'        => 'pending',
                'read_by_owner' => false,
            ]);
        }

        // ---- Requests ke buku Bang Harun ----
        if ($tobi && $b4) {
            BorrowRequest::create([
                'book_id'       => $b4->id,
                'user_id'       => $tobi->id,
                'borrower_name' => $tobi->name,
                'full_name'     => $tobi->name,
                'phone'         => '087788990011',
                'email'         => $tobi->email,
                'message'       => 'Pinjam dong, sudah lama pengen baca.',
                'borrow_date'   => now()->addDays(2),
                'return_date'   => now()->addDays(9),
                'status'        => 'approved',
                'read_by_owner' => true,
            ]);
        }

        if ($bro && $b5) {
            BorrowRequest::create([
                'book_id'       => $b5->id,
                'user_id'       => $bro->id,
                'borrower_name' => $bro->name,
                'full_name'     => $bro->name,
                'phone'         => '08111222333',
                'email'         => $bro->email,
                'message'       => 'Kak, boleh pinjam Laskar Pelangi? Tugas literasi kampus nih.',
                'borrow_date'   => now()->addDays(1),
                'return_date'   => now()->addDays(7),
                'status'        => 'pending',
                'read_by_owner' => false,
            ]);
        }

        // ---- Request ke buku Bro (inbox Bro tidak kosong) ----
        if ($bro) {
            $broBook = Book::where('user_id', $bro->id)->first();
            if ($broBook && $andrani) {
                BorrowRequest::create([
                    'book_id'       => $broBook->id,
                    'user_id'       => $andrani->id,
                    'borrower_name' => $andrani->name,
                    'full_name'     => $andrani->name,
                    'phone'         => '081234567890',
                    'email'         => $andrani->email,
                    'message'       => 'Halo Bro, aku pengen banget baca The Psychology of Money. Boleh pinjam weekend ini?',
                    'borrow_date'   => now()->addDays(1),
                    'return_date'   => now()->addDays(5),
                    'status'        => 'pending',
                    'read_by_owner' => false,
                ]);
            }
        }
    }
}
