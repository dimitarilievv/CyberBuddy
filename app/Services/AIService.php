<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;
use Illuminate\Support\Facades\Log;

class AIService
{
    private string $apiKey;
    private string $baseUrl;

    public function __construct()
    {
        $this->apiKey = (string) config('services.gemini.api_key');
        if ($this->apiKey === '') {
            throw new RuntimeException('Gemini API key is missing. Set services.gemini.api_key');
        }

        $this->baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent';
    }

    // Raw request (full JSON response)
    public function askRaw(string $prompt): array
    {
        $response = Http::timeout(60)
            ->post("{$this->baseUrl}?key={$this->apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt],
                        ],
                    ],
                ],
                'generationConfig' => [
                    'temperature' => 0.3,
                    'maxOutputTokens' => 1200,
                ],
            ]);

        $json = $response->json();
        Log::debug('AIService.askRaw HTTP response', ['response' => $json]);

        return $json ?? [];
    }

    // Simple text response
    public function ask(string $prompt): string
    {
        $json = $this->askRaw($prompt);

        return (string) data_get($json, 'candidates.0.content.parts.0.text', '');
    }

    // ─────────────────────────────────────────────
    //  ASK JSON (Safe)
    // ─────────────────────────────────────────────

    public function askJson(string $prompt, int $retries = 3): array
    {
        $attempt = 0;

        do {
            $attempt++;

            $raw = $this->askRaw($prompt);

            $finishReason = (string) data_get($raw, 'candidates.0.finishReason', '');
            $responseText = (string) data_get($raw, 'candidates.0.content.parts.0.text', '');

            if ($finishReason === 'MAX_TOKENS') {
                Log::warning("AI response truncated (MAX_TOKENS). Attempt $attempt");
                continue;
            }

            if (trim($responseText) === '') {
                Log::warning("AI returned empty response. Attempt $attempt");
                continue;
            }

            $text = $this->extractJson($responseText);
            $text = $this->sanitizeJson($text);
            $text = $this->autoCloseJson($text);

            $decoded = json_decode($text, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $decoded;
            }

            // Recovery if still invalid
            if (substr_count($text, '{') > substr_count($text, '}')) {
                $text .= str_repeat('}', substr_count($text, '{') - substr_count($text, '}'));
            }
            if (substr_count($text, '[') > substr_count($text, ']')) {
                $text .= str_repeat(']', substr_count($text, '[') - substr_count($text, ']'));
            }

            $decoded = @json_decode($text, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $decoded;
            }

            Log::warning("AI JSON parse failed. Attempt $attempt: " . json_last_error_msg(), [
                'raw_text' => $responseText,
                'parsed_text' => $text,
            ]);

            sleep(1);
        } while ($attempt < $retries);

        Log::error("AI askJson failed after $retries attempts", ['prompt' => $prompt]);
        return [];
    }

    // ─────────────────────────────────────────────
    //  HELPERS
    // ─────────────────────────────────────────────

    private function extractJson(string $text): string
    {
        if (preg_match('/```(?:json)?\s*([\s\S]*?)```/', $text, $matches)) {
            return trim($matches[1]);
        }

        // If model adds extra text, grab JSON between first { and last }
        $start = strpos($text, '{');
        $end   = strrpos($text, '}');
        if ($start !== false && $end !== false && $end > $start) {
            return trim(substr($text, $start, $end - $start + 1));
        }

        return trim($text);
    }

    private function sanitizeJson(string $json): string
    {
        $json = preg_replace("/\r|\n/", " ", $json);
        $json = str_replace(['“', '”'], '"', $json);

        return $json;
    }

    private function autoCloseJson(string $json): string
    {
        $openCurly = substr_count($json, '{');
        $closeCurly = substr_count($json, '}');
        $openSquare = substr_count($json, '[');
        $closeSquare = substr_count($json, ']');

        return $json
            . str_repeat('}', max(0, $openCurly - $closeCurly))
            . str_repeat(']', max(0, $openSquare - $closeSquare));
    }
}
