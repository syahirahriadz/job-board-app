<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class DeepseekService
{
    public function generate($prompt)
    {
        $response = Http::timeout(120)
            ->connectTimeout(30)
            ->withHeaders([
                'Authorization' => 'Bearer ' . config('services.deepseek.key'),
                'Content-Type' => 'application/json',
            ])
            ->post(
                config('services.deepseek.base_url') . '/chat/completions',
                [
                    'model' => 'deepseek-chat',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are an AI that writes professional job descriptions.',
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt,
                        ],
                    ],
                ]
            );

        if ($response->failed()) {
            throw new \Exception('Deepseek API request failed: ' . $response->body());
        }

        $data = $response->json();

        return $data['choices'][0]['message']['content'] ?? 'No response generated';
    }
}
