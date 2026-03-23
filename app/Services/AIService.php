<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class AIService
{
    private string $apiKey;
    private string $baseUrl;

    public function __construct()
    {
        $this->apiKey = (string) config('services.gemini.api_key');

        // You can keep flash model, just be consistent with your existing config
        $this->baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent';
    }

    public function ask(string $prompt): string
    {
        if ($this->apiKey === '') {
            throw new RuntimeException('Gemini API key is missing. Set services.gemini.api_key');
        }

        $response = Http::timeout(60)
            ->post("{$this->baseUrl}?key={$this->apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt],
                        ],
                    ],
                ],
                // Optional: keep answers more deterministic
                'generationConfig' => [
                    'temperature' => 0.4,
                    'maxOutputTokens' => 1200,
                ],
            ]);

        return (string) data_get($response->json(), 'candidates.0.content.parts.0.text', 'No response.');
    }

    public function askJson(string $prompt): array
    {
        $strictPrompt = $prompt . "\n\nIMPORTANT: Return ONLY valid JSON. No markdown, no explanation, no extra text.";

        $text = $this->ask($strictPrompt);
        $json = $this->extractJson($text);

        return json_decode($json, true) ?? [];
    }

    private function extractJson(string $text): string
    {
        // If Gemini returns ```json ... ``` we still support it
        if (preg_match('/```(?:json)?\s*([\s\S]*?)```/', $text, $matches)) {
            return trim($matches[1]);
        }

        return trim($text);
    }
}
