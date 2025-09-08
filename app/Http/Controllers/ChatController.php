<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;
use Exception;

class ChatController extends Controller
{
    public function getMessages()
    {
        return Message::with('user')->orderBy('created_at')->get();
    }

    public function sendMessage(Request $request)
    {
        // Message client
        $clientMessage = Message::create([
            'user_id' => auth()->id(),
            'message' => $request->message,
            'sender'  => 'client'
        ]);

        $aiMessage = null;

        if ($request->ai) {
            $aiResponse = $this->getAIResponse($request->message);

            $aiMessage = Message::create([
                'user_id' => null,
                'message' => $aiResponse,
                'sender'  => 'ai'
            ]);
        }

        return response()->json([
            'client' => $clientMessage,
            'ai'     => $aiMessage
        ]);
    }

    private function getAIResponse($message)
    {
        $apiKey = config('openai.api_key');

        if (empty($apiKey)) {
            return "[Simulation] La clé OpenAI n'est pas configurée.";
        }

        try {
            $result = OpenAI::chat()->create([
                'model'    => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => 'Tu es un assistant virtuel qui aide les clients.'],
                    ['role' => 'user',   'content' => $message],
                ],
            ]);

            return $result->choices[0]->message->content ?? "[Simulation] Pas de réponse générée.";
        } catch (Exception $e) {
            // Si quota dépassé ou autre erreur → simulation
            return "[Simulation] Réponse AI pour : " . $message;
        }
    }
}
