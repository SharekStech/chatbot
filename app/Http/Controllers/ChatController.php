<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;




class ChatController extends Controller
{


    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        $message = trim($request->input('message'));

        $answerFromDB = DB::table('questions')
            ->where('question', 'like', '%' . $message . '%')
            ->value('answer');

        if ($answerFromDB) {
            return response()->json([
                'reply' => $answerFromDB,
                'source' => 'database'
            ]);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a helpful assistant that responds in Bengali.'],
                    ['role' => 'user', 'content' => $message],
                ],
                'temperature' => 0.7,
                'max_tokens' => 500
            ]);

            if ($response->failed()) {
                \Log::error('API Error: ' . $response->body());
                return response()->json([
                    'reply' => 'API Connection Problem: ' . $response->status()
                ]);
            }

            $data = $response->json();

            if (!isset($data['choices'][0]['message']['content'])) {
                \Log::error('Invalid API Response: ' . json_encode($data));
                return response()->json([
                    'reply' => 'Unexpected API response'
                ]);
            }

            return response()->json([
                'reply' => $data['choices'][0]['message']['content']
            ]);
        } catch (\Exception $e) {
            \Log::error('Exception: ' . $e->getMessage());
            return response()->json([
                'reply' => 'Problem: ' . $e->getMessage()
            ]);
        }
    }
}
