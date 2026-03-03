<?php
//
//namespace App\Services;
//
//use Illuminate\Support\Facades\Http;
//
//class AIService
//{
//    private string $apiKey;
//    private string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent';
//    public function __construct()
//    {
//        $this->apiKey = config('services.gemini.api_key');
//    }
//
//    /**
//     * Испрати prompt до Gemini и добиј одговор
//     */
//    public function ask(string $prompt): string
//    {
//        $response = Http::post("{$this->baseUrl}?key={$this->apiKey}", [
//            'contents' => [
//                [
//                    'parts' => [
//                        ['text' => $prompt]
//                    ]
//                ]
//            ]
//        ]);
//
//        return $response->json('candidates.0.content.parts.0.text', 'Нема одговор.');
//    }
//
//    /**
//     * Испрати prompt и добиј JSON одговор
//     */
//    public function askJson(string $prompt): array
//    {
//        $response = $this->ask($prompt . "\n\nОдговори САМО во валиден JSON формат, без додатен текст.");
//
//        $json = $this->extractJson($response);
//
//        return json_decode($json, true) ?? [];
//    }
//
//    private function extractJson(string $text): string
//    {
//        if (preg_match('/```(?:json)?\s*([\s\S]*?)```/', $text, $matches)) {
//            return trim($matches[1]);
//        }
//        return trim($text);
//    }
//}
