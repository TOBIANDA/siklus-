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
        $adidharma = User::where('email', 'adidharma@gmail.com')->first();
        $harun     = User::where('email', 'bangharun@gmail.com')->first();
        $tobi      = User::where('email', 'tobi@gmail.com')->first();
        $budi      = User::where('email', 'budisantoso@gmail.com')->first();
        $siti      = User::where('email', 'sitiaminah@gmail.com')->first();
        $joko      = User::where('email', 'joko@gmail.com')->first();
        $andrani   = User::where('email', 'andrani@gmail.com')->first();
        $bro       = User::where('email', 'kuahsayurgamingyo@gmail.com')->first();

        if (!$adidharma || !$harun || !$tobi || !$budi || !$siti || !$joko || !$andrani || !$bro) {
            return;
        }

        // Helper untuk buat percakapan antara 2 orang
        // $conv = [ [sender, recipient, pesan, menit_lalu], ... ]
        $createConversation = function (array $messages, BorrowRequest $req = null) {
            $base = now()->subHours(2);
            foreach ($messages as $i => [$sender, $recipient, $content]) {
                Message::create([
                    'sender_id'        => $sender->id,
                    'recipient_id'     => $recipient->id,
                    'borrow_request_id'=> $req?->id,
                    'content'          => $content,
                    'read_at'          => now(), // semua sudah dibaca kecuali yang terakhir
                    'created_at'       => $base->copy()->addMinutes($i * 3),
                    'updated_at'       => $base->copy()->addMinutes($i * 3),
                ]);
            }
        };

        // Ambil borrow requests per pasangan
        $reqAndrani_Adidharma = BorrowRequest::where('user_id', $andrani->id)
            ->whereHas('book', fn($q) => $q->where('user_id', $adidharma->id))->first();

        $reqBudi_Adidharma = BorrowRequest::where('user_id', $budi->id)
            ->whereHas('book', fn($q) => $q->where('user_id', $adidharma->id))->first();

        $reqSiti_Adidharma = BorrowRequest::where('user_id', $siti->id)
            ->whereHas('book', fn($q) => $q->where('user_id', $adidharma->id))->first();

        $reqJoko_Adidharma = BorrowRequest::where('user_id', $joko->id)
            ->whereHas('book', fn($q) => $q->where('user_id', $adidharma->id))->first();

        $reqTobi_Harun = BorrowRequest::where('user_id', $tobi->id)
            ->whereHas('book', fn($q) => $q->where('user_id', $harun->id))->first();

        $reqBro_Harun = BorrowRequest::where('user_id', $bro->id)
            ->whereHas('book', fn($q) => $q->where('user_id', $harun->id))->first();

        $reqAndrani_Bro = BorrowRequest::where('user_id', $andrani->id)
            ->whereHas('book', fn($q) => $q->where('user_id', $bro->id))->first();

        // ── 1. Andrani ↔ Adidharma (The Little Prince, pending) ──────────────
        $createConversation([
            [$andrani,   $adidharma, 'Halo kak! Saya lihat buku The Little Prince kamu di Siklus.'],
            [$adidharma, $andrani,   'Halo! Iya betul, masih tersedia kok.'],
            [$andrani,   $adidharma, 'Boleh pinjam minggu ini nggak? Saya di Surabaya, bisa COD akhir pekan.'],
            [$adidharma, $andrani,   'Hmm, saya di Malang. Tapi bisa kirim via JNE kalau mau.'],
            [$andrani,   $adidharma, 'Oh bisa! Nanti saya submit request ya kak.'],
            [$adidharma, $andrani,   'Siap, saya cek segera setelah request masuk 👍'],
            [$andrani,   $adidharma, 'Udah saya submit kak, ditunggu konfirmasinya!'],
        ], $reqAndrani_Adidharma);

        // ── 2. Budi ↔ Adidharma (Atomic Habits, pending) ─────────────────────
        $createConversation([
            [$budi,      $adidharma, 'Kak, Atomic Habits masih available?'],
            [$adidharma, $budi,      'Masih! Lagi nggak dipinjam siapapun.'],
            [$budi,      $adidharma, 'Mantap! Saya lagi nge-grind habits baru nih 😅'],
            [$adidharma, $budi,      'Haha pas banget, buku ini recomended banget!'],
            [$budi,      $adidharma, 'Saya submit request ya. Bisa 7 hari kan?'],
            [$adidharma, $budi,      'Bisa, asal balik tepat waktu ya.'],
        ], $reqBudi_Adidharma);

        // ── 3. Siti ↔ Adidharma (Negeri 5 Menara, rejected) ─────────────────
        $createConversation([
            [$siti,      $adidharma, 'Halo kak, boleh pinjam Negeri 5 Menara untuk keponakan saya?'],
            [$adidharma, $siti,      'Halo! Untuk anak berapa tahun?'],
            [$siti,      $adidharma, 'SMP kelas 2, kira-kira cocok nggak ya?'],
            [$adidharma, $siti,      'Cocok banget, temanya inspiratif untuk anak muda.'],
            [$siti,      $adidharma, 'Oke saya submit request ya kak!'],
            [$adidharma, $siti,      'Maaf ya bu Siti, kebetulan minggu ini buku sedang diperlukan sendiri. Lain kali boleh 🙏'],
            [$siti,      $adidharma, 'Oh tidak apa-apa kak, terima kasih ya sudah menjawab dengan cepat!'],
        ], $reqSiti_Adidharma);

        // ── 4. Joko ↔ Adidharma (The Little Prince, pending) ─────────────────
        $createConversation([
            [$joko,      $adidharma, 'Halo kak, buku The Little Prince edisi bahasa apa ini?'],
            [$adidharma, $joko,      'Edisi terjemahan Indonesia, tapi bagus banget kualitas terjemahannya.'],
            [$joko,      $adidharma, 'Oh keren! Kondisi buku gimana?'],
            [$adidharma, $joko,      'Masih bagus, cover oke, halaman lengkap semua.'],
            [$joko,      $adidharma, 'Siap, saya request dulu ya kak!'],
        ], $reqJoko_Adidharma);

        // ── 5. Tobi ↔ Bang Harun (The Art of Loving, approved) ───────────────
        $createConversation([
            [$tobi,  $harun, 'Kak Harun, The Art of Loving masih bisa dipinjam?'],
            [$harun, $tobi,  'Bisa dong! Sudah lama nggak ada yang minta.'],
            [$tobi,  $harun, 'Wah kebetulan! Saya penasaran sama filsafat Erich Fromm.'],
            [$harun, $tobi,  'Bagus banget buku itu, banyak insight soal cinta yang beda dari biasanya.'],
            [$tobi,  $harun, 'Oke saya submit request ya. Satu minggu cukup?'],
            [$harun, $tobi,  'Cukup, tapi kalau butuh lebih bilang aja.'],
            [$harun, $tobi,  'Request sudah saya approve! Kabari ya pas mau ambil 📚'],
            [$tobi,  $harun, 'Siap kak! Makasih banyak, nanti saya hubungi untuk COD.'],
        ], $reqTobi_Harun);

        // ── 6. Bro ↔ Bang Harun (Laskar Pelangi, pending) ────────────────────
        $createConversation([
            [$bro,   $harun, 'Halo kak Harun! Laskar Pelangi masih available?'],
            [$harun, $bro,   'Halo! Masih ada kok, belum ada yang minjam.'],
            [$bro,   $harun, 'Mantap! Ini buat tugas literasi kampus. Boleh pinjam seminggu?'],
            [$harun, $bro,   'Boleh! Biasanya saya minta return tepat waktu ya.'],
            [$bro,   $harun, 'Siap kak, pasti saya kembalikan on time. Submit request ya!'],
            [$harun, $bro,   'Oke saya tunggu requestnya 👍'],
        ], $reqBro_Harun);

        // ── 7. Andrani ↔ Bro (The Psychology of Money, pending) ──────────────
        $createConversation([
            [$andrani, $bro, 'Halo Bro! Lihat buku The Psychology of Money kamu di Siklus.'],
            [$bro,     $andrani, 'Halo Andrani! Iya itu buku favorit aku.'],
            [$andrani, $bro, 'Wah beneran? Boleh pinjem weekend ini nggak?'],
            [$bro,     $andrani, 'Boleh! Kamu di mana? Bisa COD di Malang nggak?'],
            [$andrani, $bro, 'Aduh saya di Surabaya... bisa kirim nggak?'],
            [$bro,     $andrani, 'Hmm bisa sih via Sicepat. Ongkir kamu yang tanggung ya 😄'],
            [$andrani, $bro, 'Deal! Oke saya submit request sekarang!'],
            [$bro,     $andrani, 'Siap, saya cek inbox segera!'],
        ], $reqAndrani_Bro);

        // ── 8. Budi ↔ Tobi (cross-chat, tidak ada borrow request) ────────────
        $createConversation([
            [$budi, $tobi, 'Tobi, kamu punya Perahu Kertas kan? Saya liat di profilmu.'],
            [$tobi, $budi, 'Iya punya! Kondisi masih bagus.'],
            [$budi, $tobi, 'Boleh pinjem? Pacar aku lagi minta dicariin buku itu haha'],
            [$tobi, $budi, 'Hahaha so sweet! Boleh kok, submit request aja.'],
            [$budi, $tobi, 'Sip! Nanti aku submit ya.'],
        ], null);

        // ── 9. Siti ↔ Joko (cross-chat, tidak ada borrow request) ───────────
        $createConversation([
            [$siti, $joko, 'Mas Joko, ada rekomendasi buku self-help yang bagus?'],
            [$joko, $siti, 'Ada! Filosofi Teras bagus banget buat mindset sehari-hari.'],
            [$siti, $joko, 'Wah itu punya siapa ya di Siklus?'],
            [$joko, $siti, 'Punya Adidharma, bisa langsung cek profilnya.'],
            [$siti, $joko, 'Makasih mas, langsung saya cek!'],
            [$joko, $siti, 'Sama-sama! Senang bisa bantu 😊'],
        ], null);

        // ── 10. Andrani ↔ Tobi (cross-chat) ──────────────────────────────────
        $createConversation([
            [$andrani, $tobi, 'Tobi! Bumi Manusia masih ada?'],
            [$tobi,    $andrani, 'Ada dong! Itu koleksi kesayangan saya.'],
            [$andrani, $tobi, 'Wah saya pengen banget baca. Kapan bisa dipinjam?'],
            [$tobi,    $andrani, 'Setelah yang sekarang balik, sekitar 2 minggu lagi.'],
            [$andrani, $tobi, 'Oke saya daftar antrian dulu ya!'],
        ], null);

        // Mark pesan terakhir setiap thread sebagai unread (read_at = null)
        // Ini supaya ada notif unread yang realistis
        $lastMessages = Message::selectRaw('MAX(id) as id')->groupBy('sender_id', 'recipient_id')->pluck('id');
        Message::whereIn('id', $lastMessages->take(5))->update(['read_at' => null]);
    }
}
