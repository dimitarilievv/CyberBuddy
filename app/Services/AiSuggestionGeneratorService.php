<?php

namespace App\Services;

use App\Models\AiContentSuggestion;
use App\Models\Question;
use App\Models\QuestionAnswer;
use Illuminate\Support\Str;

class AiSuggestionGeneratorService
{
    public function __construct(
        private AIService $aiService
    ) {}

    /**
     * Generates AI suggestions based on students' mistakes and saves them as pending.
     *
     * @return int number of suggestions created
     */
    public function generateAndStoreSuggestions(int $limitTopics = 3): int
    {
        $topics = $this->buildWeakTopicsReport($limitTopics);

        if (empty($topics)) {
            logger()->info('AiSuggestionGeneratorService: no weak topics found, falling back to default general topic');
            $topics = [[
                'topic' => 'general',
                'total_attempts' => 0,
                'wrong_attempts' => 0,
                'accuracy_percent' => 0.0,
                'example_questions' => [],
            ]];
        }

        $prompt = $this->buildPrompt($topics);

        $result = $this->aiService->askJson($prompt);
        logger()->info('Gemini result', ['result' => $result]);

        // Expected JSON:
        // {
        //   "suggestions": [
        //     {"content_type":"question","title":"...","suggested_content":"..."},
        //     ...
        //   ]
        // }
        $suggestions = $result['suggestions'] ?? [];

        if (empty($suggestions)) {
            logger()->warning('AiSuggestionGeneratorService: AI returned no suggestions', ['raw' => $result]);
            return 0;
        }

        $created = 0;

        foreach ($suggestions as $s) {
            $contentType = $s['content_type'] ?? null;
            $title = $s['title'] ?? null;
            $suggestedContent = $s['suggested_content'] ?? null;

            $contentType = $s['content_type'] ?? $s['contentType'] ?? $s['type'] ?? null;

            if ($contentType === 'question' || $contentType === 'questions') {
                $contentType = 'quiz';
            }
            if ($contentType === 'resource') {
                $contentType = 'lesson';
            }

            $allowed = ['module', 'lesson', 'scenario', 'quiz', 'tip'];
            if (!in_array($contentType, $allowed, true))
                continue;
            if (!is_string($title) || trim($title) === '') {
                continue;
            }
            if (!is_string($suggestedContent) || trim($suggestedContent) === '') {
                continue;
            }

            AiContentSuggestion::create([
                'user_id' => 1,
                'content_type' => $contentType,
                'title' => $title,
                'suggested_content' => $suggestedContent,
                'status' => 'pending',
                'admin_notes' => null,
                'reviewed_by' => null,
                'reviewed_at' => null,
            ]);

            $created++;
        }

        logger()->info('AiSuggestionGeneratorService: created suggestions count', ['created' => $created]);

        return $created;
    }

    private function buildWeakTopicsReport(int $limitTopics): array
    {
        // Pull recent answers and join with question text.
        // Adjust table/columns if your schema differs.
        $answers = QuestionAnswer::query()
            ->select(['question_answers.is_correct', 'question_answers.question_id'])
            ->with(['question:id,question_text'])
            ->latest('question_answers.created_at')
            ->limit(800)
            ->get();

        if ($answers->isEmpty()) {
            return [];
        }

        // Map question_id => topic
        $topicStats = []; // topic => ['total'=>x,'wrong'=>y,'examples'=>[]]

        foreach ($answers as $a) {
            $q = $a->question;
            if (!$q) continue;

            $topic = $this->detectTopicFromQuestionText($q->question_text);

            $topicStats[$topic]['total'] = ($topicStats[$topic]['total'] ?? 0) + 1;
            $topicStats[$topic]['wrong'] = ($topicStats[$topic]['wrong'] ?? 0) + ($a->is_correct ? 0 : 1);

            if (count($topicStats[$topic]['examples'] ?? []) < 3) {
                $topicStats[$topic]['examples'][] = Str::limit($q->question_text, 160);
            }
        }

        // Compute accuracy and sort by worst accuracy
        $report = [];
        foreach ($topicStats as $topic => $stats) {
            $total = (int) ($stats['total'] ?? 0);
            $wrong = (int) ($stats['wrong'] ?? 0);

            // Do not drop topics yet; collect full report so we can fall back if needed
            $accuracy = $total > 0 ? round((($total - $wrong) / $total) * 100, 1) : 0.0;

            $report[] = [
                'topic' => $topic,
                'total_attempts' => $total,
                'wrong_attempts' => $wrong,
                'accuracy_percent' => $accuracy,
                'example_questions' => $stats['examples'] ?? [],
            ];
        }

        // Primary filtering: keep only topics with enough samples (>=10) and weak accuracy (<=50)
        usort($report, fn($a, $b) => $a['accuracy_percent'] <=> $b['accuracy_percent']);

        $filtered = array_values(array_filter($report, fn($t) => $t['total_attempts'] >= 10 && $t['accuracy_percent'] <= 50));
        if (!empty($filtered)) {
            return array_slice($filtered, 0, $limitTopics);
        }

        // Fallback: if we couldn't find topics with >=10 samples, relax the sample requirement
        // and return the worst topics by accuracy (still targeting weak ones first).
        // Log so we can diagnose why AI generated 0 suggestions.
        logger()->info('AiSuggestionGeneratorService: not enough samples per topic; falling back to weaker sample filter', [
            'topics_total' => count($report),
            'top_candidates' => array_map(fn($t) => [$t['topic'], $t['total_attempts'], $t['accuracy_percent']], array_slice($report, 0, 10)),
        ]);

        // Try weaker filter: accept topics with at least 3 samples and accuracy <= 60
        $weaker = array_values(array_filter($report, fn($t) => $t['total_attempts'] >= 3 && $t['accuracy_percent'] <= 60));
        if (!empty($weaker)) {
            return array_slice($weaker, 0, $limitTopics);
        }

        // Final fallback: just return the worst topics regardless of sample size (useful for small datasets)
        logger()->warning('AiSuggestionGeneratorService: final fallback — returning worst topics regardless of sample size');
        return array_slice($report, 0, $limitTopics);
    }

    private function detectTopicFromQuestionText(string $text): string
    {
        $t = Str::lower($text);

        return match (true) {
            str_contains($t, 'phishing') || str_contains($t, 'suspicious email') || str_contains($t, 'scam') => 'phishing',
            str_contains($t, 'password') || str_contains($t, 'passphrase') => 'passwords',
            str_contains($t, '2fa') || str_contains($t, 'two-factor') || str_contains($t, 'authentication') => '2fa',
            str_contains($t, 'link') || str_contains($t, 'url') => 'links & urls',
            str_contains($t, 'privacy') || str_contains($t, 'personal information') => 'privacy',
            default => 'general',
        };
    }

    private function buildPrompt(array $topicsReport): string
    {
        $topicsJson = json_encode($topicsReport, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        return <<<PROMPT
You are an education content assistant for a children's cybersecurity learning platform.

Based on the following weak-topics report from student mistakes, generate new content suggestions that help students improve.

RULES:
- Return JSON with this exact shape:
  {
    "suggestions": [
      {
        "content_type": "module|lesson|scenario|quiz|tip",
        "title": "short title",
        "suggested_content": "full content text"
      }
    ]
  }
- Create 3 to 6 suggestions total.
- At least 1 must be "quiz" and at least 1 must be "tip".
- Keep language simple and suitable for kids.
- Suggestions should directly target the weak topics.

WEAK TOPICS REPORT (JSON):
$topicsJson
PROMPT;
    }
}
