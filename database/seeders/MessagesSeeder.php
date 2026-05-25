<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\BorrowRequest;
use App\Models\Message;
use Carbon\Carbon;

class MessagesSeeder extends Seeder
{
    public function run(): void
    {
        // Bersihkan pesan lama
        \DB::table('messages')->delete();

        $adidharma = User::where('email', 'adidharma@gmail.com')->first();
        $harun     = User::where('email', 'bangharun@gmail.com')->first();
        $tobi      = User::where('email', 'tobi@gmail.com')->first();
        $budi      = User::where('email', 'budisantoso@gmail.com')->first();
        $siti      = User::where('email', 'sitiaminah@gmail.com')->first();
        $joko      = User::where('email', 'joko@gmail.com')->first();
        $andrani   = User::where('email', 'andrani@gmail.com')->first();
        $bro       = User::where('email', 'kuahsayurgamingyo@gmail.com')->first();

        if (!$adidharma || !$harun || !$tobi || !$bro) return;

        // Helper: buat percakapan antara 2 orang
        $createConversation = function (array $messages, $hoursAgo = 2, bool $lastUnread = true) {
            $base = now()->subHours($hoursAgo);
            $count = count($messages);
            foreach ($messages as $i => [$sender, $recipient, $content]) {
                $isLast = ($i === $count - 1);
                Message::create([
                    'sender_id'        => $sender->id,
                    'recipient_id'     => $recipient->id,
                    'borrow_request_id'=> null,
                    'content'          => $content,
                    'read_at'          => ($isLast && $lastUnread) ? null : now(),
                    'created_at'       => $base->copy()->addMinutes($i * 4),
                    'updated_at'       => $base->copy()->addMinutes($i * 4),
                ]);
            }
        };

        // ── 1. BRO ↔ Bang Harun — chat soal meminjam buku ──────────────────────
        $createConversation([
            [$bro,   $harun, 'Halo kak Harun! Bukumu yang baru masih tersedia?'],
            [$harun, $bro,   'Halo! Yang mana nih, The Art of Loving?'],
            [$bro,   $harun, 'Iya kak, udah lama pengen baca tapi selalu ketinggalan 😅'],
            [$harun, $bro,   'Haha masih ada kok! Saya request ya?'],
            [$bro,   $harun, 'Udah saya submit kak, ditunggu konfirmasinya ya!'],
            [$harun, $bro,   'Oke saya approve sekarang. Nanti hubungi lagi soal COD 📚'],
            [$bro,   $harun, 'Siap kak! Makasih banyak 🙏'],
        ], 3, false);

        // ── 2. BRO ↔ Tobi — chat soal Bumi Manusia (approved/dipinjam) ────────
        $createConversation([
            [$bro,  $tobi, 'Tobi, Bumi Manusia masih ada?'],
            [$tobi, $bro,  'Masih! Baru balik dari peminjam sebelumnya.'],
            [$bro,  $tobi, 'Wah cocok banget! Aku lagi cari bacaan berat nih.'],
            [$tobi, $bro,  'Bumi Manusia emang berat tapi bagus banget, dijamin ketagihan.'],
            [$bro,  $tobi, 'Oke deh, aku submit request ya!'],
            [$tobi, $bro,  'Sudah aku approve! Bisa COD di Bandung nggak? Atau mau dikirim?'],
            [$bro,  $tobi, 'Kirim aja kak, aku tanggung ongkirnya.'],
            [$tobi, $bro,  'Oke, nanti aku pack rapih ya. Estimasi 2 hari sampai.'],
            [$bro,  $tobi, 'Siap! Makasih tobi 😊'],
        ], 48, false);

        // ── 3. BRO ↔ Andrani — Andrani mau pinjam Psychology of Money milik BRO
        $createConversation([
            [$andrani, $bro, 'Halo Bro! Lihat buku The Psychology of Money kamu di Siklus.'],
            [$bro,     $andrani, 'Halo Andrani! Iya itu buku favorit aku.'],
            [$andrani, $bro, 'Wah beneran? Boleh pinjem weekend ini nggak?'],
            [$bro,     $andrani, 'Boleh! Kamu di mana? Bisa COD di Malang nggak?'],
            [$andrani, $bro, 'Aduh saya di Surabaya... bisa kirim nggak?'],
            [$bro,     $andrani, 'Hmm bisa sih via Sicepat. Ongkir kamu yang tanggung ya 😄'],
            [$andrani, $bro, 'Deal! Oke saya submit request sekarang!'],
            [$andrani, $bro, 'Udah aku submit Bro, cek inbox ya!'],
        ], 1, true); // pesan terakhir unread → BRO ada notif

        // ── 4. BRO ↔ Joko — Joko mau pinjam Psychology of Money ───────────────
        $createConversation([
            [$joko, $bro, 'Bro, buku psychology of money nya boleh pinjam nggak?'],
            [$bro,  $joko, 'Boleh! Tapi lagi ada antrian dari Andrani nih.'],
            [$joko, $bro, 'Oh gitu, nanti aku submit request dulu ya, siapa tau lebih cepet.'],
            [$bro,  $joko, 'Oke aja, nanti aku kabarin kalau udah kosong.'],
            [$joko, $bro, 'Siap Bro! Makasih ya 👍'],
            [$joko, $bro, 'Udah aku submit request nya Bro!'],
        ], 5, true); // pesan terakhir unread

        // ── 5. BRO ↔ Adidharma — obrolan biasa ─────────────────────────────────
        $createConversation([
            [$bro,       $adidharma, 'Kak Adi, Filosofi Teras udah aku balikin ya minggu lalu.'],
            [$adidharma, $bro,       'Oh iya sudah aku terima, makasih ya kondisinya masih mulus 👍'],
            [$bro,       $adidharma, 'Hehe iya aku jaga banget, buku kak Adi bagus-bagus soalnya.'],
            [$adidharma, $bro,       'Haha senang deh kalau gitu. Nanti ada buku baru mau aku upload nih.'],
            [$bro,       $adidharma, 'Wah serius? Genre apa kak?'],
            [$adidharma, $bro,       'Self-improvement lagi, tapi yang fokus ke produktivitas.'],
            [$bro,       $adidharma, 'Mantap! Notify aku ya kalau udah upload 🙏'],
        ], 72, false);

        // ── 6. Andrani ↔ Adidharma (The Little Prince, pending) ─────────────────
        if ($andrani) {
            $createConversation([
                [$andrani,   $adidharma, 'Halo kak! Saya lihat buku The Little Prince kamu di Siklus.'],
                [$adidharma, $andrani,   'Halo! Iya betul, masih tersedia kok.'],
                [$andrani,   $adidharma, 'Boleh pinjam minggu ini nggak? Saya di Surabaya, bisa COD akhir pekan.'],
                [$adidharma, $andrani,   'Hmm, saya di Malang. Tapi bisa kirim via JNE kalau mau.'],
                [$andrani,   $adidharma, 'Oh bisa! Nanti saya submit request ya kak.'],
                [$adidharma, $andrani,   'Siap, saya cek segera setelah request masuk 👍'],
                [$andrani,   $adidharma, 'Udah saya submit kak, ditunggu konfirmasinya!'],
            ], 6, false);
        }

        // ── 7. Tobi ↔ Bang Harun (The Art of Loving, approved) ───────────────
        $createConversation([
            [$tobi,  $harun, 'Kak Harun, The Art of Loving masih bisa dipinjam?'],
            [$harun, $tobi,  'Bisa dong! Sudah lama nggak ada yang minta.'],
            [$tobi,  $harun, 'Wah kebetulan! Saya penasaran sama filsafat Erich Fromm.'],
            [$harun, $tobi,  'Bagus banget buku itu, banyak insight soal cinta yang beda dari biasanya.'],
            [$tobi,  $harun, 'Oke saya submit request ya. Satu minggu cukup?'],
            [$harun, $tobi,  'Cukup, tapi kalau butuh lebih bilang aja.'],
            [$harun, $tobi,  'Request sudah saya approve! Kabari ya pas mau ambil 📚'],
            [$tobi,  $harun, 'Siap kak! Makasih banyak, nanti saya hubungi untuk COD.'],
        ], 24, false);
    }
}
