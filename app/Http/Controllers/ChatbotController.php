<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    public function sendMessage(Request $request)
    {
        // Ambil pesan dari frontend
        $message = trim($request->input('message')); // hapus spasi / newline yang bisa ganggu parsing
        $sender = $request->ip();

        try {
            // URL endpoint Rasa (pastikan di .env sudah benar)
            $rasaApiUrl = env('RASA_API_URL', 'https://chatbot.adatku.my.id/webhooks/rest/webhook');

            // Kirim data ke Rasa menggunakan JSON dengan header yang benar
            $response = Http::timeout(15)
                ->withOptions([
                    'verify' => false, // matikan SSL verification (aman untuk tunnel dev)
                ])
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->post($rasaApiUrl, [
                    'sender' => $sender,
                    'message' => $message,
                ]);

            // Log untuk debugging
            Log::info('Pesan dikirim ke Rasa', ['sender' => $sender, 'message' => $message]);
            Log::info('Respons dari Rasa', ['data' => $response->json()]);

            // Jika respons sukses
            if ($response->successful()) {
                // Cegah error jika Rasa kirim response kosong
                $data = $response->json();
                if (empty($data)) {
                    return response()->json([
                        ['text' => 'Maaf, server sedang sibuk. Coba lagi nanti.']
                    ]);
                }
                return response()->json($data);
            }

            // Jika gagal (status code bukan 2xx)
            Log::error('Rasa server error', ['status' => $response->status()]);
            return response()->json([
                'error' => 'Rasa server error. Status code: ' . $response->status()
            ], 500);

        } catch (\Exception $e) {
            // Tangani error koneksi atau timeout
            Log::error('Gagal menghubungi Rasa server', ['error' => $e->getMessage()]);
            return response()->json([
                'error' => 'Gagal menghubungi Rasa server: ' . $e->getMessage()
            ], 500);
        }
    }
}
