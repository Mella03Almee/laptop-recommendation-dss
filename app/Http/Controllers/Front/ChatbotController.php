<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Alternatif;
use App\Models\ChatSession;
use App\Models\ChatMessage;

class ChatbotController extends Controller
{
    public function tanya(Request $request)
    {
        $pesanUser = $request->input('pesan');

        if (empty($pesanUser)) {
            return response()->json(['jawaban' => 'Silakan ketik pertanyaan Anda.']);
        }

        try {
            // 1. KELOLA SESI PENGUNJUNG (ANONIM)
            $sessionId = session()->get('chat_user_id');
            if (!$sessionId) {
                $sessionId = 'user-' . rand(10000, 99999);
                session()->put('chat_user_id', $sessionId);
            }

            // Simpan atau ambil data sesi dari Database
            ChatSession::firstOrCreate(
                ['id' => $sessionId],
                [
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]
            );

            // Simpan Pesan User ke DB
            ChatMessage::create([
                'chat_session_id' => $sessionId,
                'sender'          => 'user',
                'pesan'           => $pesanUser,
            ]);

            // 2. SIAPKAN CONTEXT & DATA SPK
            $teksDataSPK = "BERIKUT ADALAH DATA LAPTOP DARI SISTEM SPK KAMI:\n";
            if (class_exists('App\Models\Alternatif')) {
                $dataLaptopDB = Alternatif::all();
                foreach ($dataLaptopDB as $item) {
                    $nama = $item->nama_alternatif ?? $item->nama_laptop ?? 'Laptop';
                    $teksDataSPK .= "- {$nama}\n";
                }
            }

            $systemPrompt = "Anda adalah Asisten Cerdas SPK Pemilihan Laptop berbasis metode Weighted Product (WP).\n"
                . "Tugas Anda:\n"
                . "1. Jawab pertanyaan pengguna secara natural, ramah, dan solutif.\n"
                . "2. Jika pengguna meminta saran laptop untuk kebutuhan/jurusan tertentu, jelaskan spesifikasi idealnya dulu, lalu rekomendasikan laptop yang sesuai berdasarkan data SPK di sistem.\n"
                . "3. INGAT CONTEXT CONVERSATION: Selalu ingat apa yang sudah dibicarakan sebelumnya.\n"
                . "4. Gunakan Bahasa Indonesia yang sopan dan lugas.\n\n"
                . $teksDataSPK;

            // 3. KELOLA RIWAYAT CHAT DI SESSION (UNTUK GEMINI)
            $history = session()->get('chatbot_history', []);

            if (empty($history)) {
                $history[] = [
                    'role'  => 'user',
                    'parts' => [['text' => $systemPrompt]]
                ];
                $history[] = [
                    'role'  => 'model',
                    'parts' => [['text' => 'Paham. Saya siap membantu pengguna memilih laptop berdasarkan data SPK.']]
                ];
            }

            $history[] = [
                'role'  => 'user',
                'parts' => [['text' => $pesanUser]]
            ];

            // 4. PANGGIL GEMINI API
            $apiKey = env('GEMINI_API_KEY');
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1/models/gemini-2.5-flash:generateContent?key=" . $apiKey, [
                'contents' => $history
            ]);

            if ($response->successful()) {
                $result = $response->json();
                $jawabanRaw = $result['candidates'][0]['content']['parts'][0]['text'] ?? 'Maaf, terjadi kendala saat memproses jawaban.';

                // Simpan Jawaban Bot ke DB
                ChatMessage::create([
                    'chat_session_id' => $sessionId,
                    'sender'          => 'bot',
                    'pesan'           => $jawabanRaw,
                ]);

                // Update Session
                $history[] = [
                    'role'  => 'model',
                    'parts' => [['text' => $jawabanRaw]]
                ];
                session()->put('chatbot_history', $history);

                // Format tampilan HTML
                $jawabanHTML = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $jawabanRaw);
                $jawabanHTML = nl2br($jawabanHTML);

                return response()->json(['jawaban' => $jawabanHTML]);
            }

            return response()->json(['jawaban' => 'Gagal terhubung ke layanan AI.']);

        } catch (\Exception $e) {
            return response()->json(['jawaban' => 'Kesalahan Server: ' . $e->getMessage()]);
        }
    }
}