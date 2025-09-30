<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Cloud\Dialogflow\V2\QueryInput;
use Google\Cloud\Dialogflow\V2\TextInput;
use Exception;
use Google\Cloud\Dialogflow\V2\Client\SessionsClient;
use Illuminate\Support\Facades\Log;
use Google\Cloud\Dialogflow\V2\DetectIntentRequest;

class ChatbotController extends Controller
{
    /**
     * Berkomunikasi dengan Dialogflow dan mengembalikan responsnya.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        try {
            $credentialsPath = config('dialogflow.credentials');
            if (!file_exists($credentialsPath)) {
                Log::error('Dialogflow credentials file not found at path: ' . $credentialsPath);
                return response()->json(['error' => 'Konfigurasi asisten virtual tidak ditemukan.'], 500);
            }

            $projectId = config('dialogflow.project_id');
            $sessionId = $request->session()->getId(); // Gunakan ID sesi Laravel untuk keunikan
            $message = $request->input('message');
            $languageCode = 'id'; // Bahasa Indonesia

            $sessionsClient = new SessionsClient([
                'credentials' => $credentialsPath
            ]);

            $sessionName = $sessionsClient->sessionName($projectId, $sessionId);

            $textInput = new TextInput();
            $textInput->setText($message);
            $textInput->setLanguageCode($languageCode);

            $queryInput = new QueryInput();
            $queryInput->setText($textInput);

            $request = new DetectIntentRequest();
            $request->setSession($sessionName);
            $request->setQueryInput($queryInput);

            $response = $sessionsClient->detectIntent($request);
            $queryResult = $response->getQueryResult();
            $fulfillmentText = $queryResult->getFulfillmentText();

            $sessionsClient->close();

            return response()->json(['reply' => $fulfillmentText]);
        } catch (Exception $e) {
            Log::error('Dialogflow Error: ' . $e->getMessage());
            return response()->json(['error' => 'Maaf, terjadi kesalahan saat menghubungi asisten virtual.'], 500);
        }
    }
}