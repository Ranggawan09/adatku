<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\PakaianAdat;
use App\Models\Reservation;

class ChatbotController extends Controller
{
    /**
     * Berkomunikasi dengan Rasa dan mengembalikan responsnya.
     * Endpoint ini akan menerima pesan dari frontend dan meneruskannya ke Rasa.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse.
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        try {
            // URL ini menunjuk ke endpoint webhook Rasa, bukan /model/parse lagi.
            $rasaServerUrl = env('RASA_SERVER_URL', 'http://127.0.0.1:5005');
            $message = $request->input('message');
            // Rasa membutuhkan 'sender' untuk melacak sesi percakapan.
            $senderId = $request->session()->getId();

            // Kirim pesan ke Rasa menggunakan endpoint webhook
            $rasaResponse = Http::timeout(30)->post("$rasaServerUrl/webhooks/rest/webhook", [ // Tambahkan timeout 30 detik
                'sender' => $senderId,
                'message' => $message
            ]);

            if ($rasaResponse->failed()) {
                throw new Exception('Gagal terhubung ke server Rasa.');
            }

            // Rasa akan mengembalikan array balasan. Kita ambil yang pertama.
            $rasaReplies = $rasaResponse->json();

            // Periksa apakah ada balasan yang valid dari Rasa
            if (empty($rasaReplies) || !isset($rasaReplies[0]['text'])) {
                throw new Exception('Rasa server returned an empty or invalid response.');
            }
            $replyText = $rasaReplies[0]['text'];

            return response()->json(['reply' => $replyText]);

        } catch (Exception $e) {
            Log::error('Rasa Chatbot Error: ' . $e->getMessage());
            return response()->json(['error' => 'Maaf, terjadi kesalahan saat menghubungi asisten virtual.'], 500);
        }
    }
}