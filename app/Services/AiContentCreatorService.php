<?php

namespace App\Services;

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Scenario;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class AiContentCreatorService
{
    public function __construct(
        private AIService $aiService
    ) {}

    // ─────────────────────────────────────────────
    //  LESSON
    // ─────────────────────────────────────────────

    public function generateLesson(int $moduleId, string $topic): Lesson
    {
        $prompt = "
        Create a cybersecurity lesson for kids (age 10-13).
        Topic: $topic
        Make sure this lesson is different from any previous lesson about this topic.
        Return JSON:
        {
            \"title\": \"...\",
            \"content\": \"...\",
            \"estimated_minutes\": 5
        }";

        $data = $this->aiService->askJson($prompt);

        if (!is_array($data) || !isset($data['title'], $data['content'])) {
            Log::error('AI Lesson generation failed', ['prompt' => $prompt, 'response' => $data]);
            return $this->createFallbackLesson($moduleId, $topic);
        }

        return Lesson::create([
            'module_id'         => $moduleId,
            'title'             => $data['title'],
            'slug'              => $this->uniqueSlug($data['title'], Lesson::class),
            'content'           => $data['content'],
            'type'              => 'text',
            'estimated_minutes' => $data['estimated_minutes'] ?? 5,
            'sort_order'        => Lesson::where('module_id', $moduleId)->max('sort_order') + 1,
            'is_published'      => false,
        ]);
    }

    private function createFallbackLesson(int $moduleId, string $topic): Lesson
    {
        $title   = ucfirst($topic);
        $content = '<h2>About ' . htmlspecialchars($topic) . '</h2>'
            . '<p>This lesson is about <strong>' . htmlspecialchars($topic) . '</strong>. '
            . 'Here are some important things to know about this topic for kids aged 10-13.</p>'
            . '<ul>'
            . '<li>Always stay safe online.</li>'
            . '<li>Ask a trusted adult if you are unsure about something.</li>'
            . '<li>Never share personal information with strangers.</li>'
            . '</ul>';

        return Lesson::create([
            'module_id'         => $moduleId,
            'title'             => $title,
            'slug'              => $this->uniqueSlug($title, Lesson::class),
            'content'           => $content,
            'type'              => 'text',
            'estimated_minutes' => 5,
            'sort_order'        => Lesson::where('module_id', $moduleId)->max('sort_order') + 1,
            'is_published'      => false,
        ]);
    }

    // ─────────────────────────────────────────────
    //  QUIZ
    // ─────────────────────────────────────────────

    public function generateQuiz(int $lessonId, string $topic): Quiz
    {
        $prompt = "
        Create a quiz (5 questions) for kids about: $topic
        Return JSON:
        {
            \"title\": \"...\",
            \"questions\": [
                {
                    \"question\": \"...\",
                    \"options\": [\"A\",\"B\",\"C\",\"D\"],
                    \"correct\": \"A\"
                }
            ]
        }";

        $data = $this->aiService->askJson($prompt);

        if (!is_array($data) || !isset($data['title'], $data['questions']) || !is_array($data['questions'])) {
            Log::error('AI Quiz generation failed', ['response' => $data]);
            return $this->createFallbackQuiz($lessonId);
        }

        $quiz = Quiz::create([
            'lesson_id'          => $lessonId,
            'title'              => $data['title'],
            'passing_score'      => 70,
            'time_limit_minutes' => 10,
            'max_attempts'       => 3,
            'is_published'       => false,
        ]);

        $optionLabels = ['A', 'B', 'C', 'D'];

        foreach ($data['questions'] as $q) {
            $assocOptions = [];
            foreach (($q['options'] ?? []) as $i => $opt) {
                $label               = $optionLabels[$i] ?? $i;
                $assocOptions[$label] = preg_replace('/^[A-D]\)\s*/', '', $opt);
            }

            $quiz->questions()->create([
                'question_text'  => $q['question'] ?? '',
                'type'           => 'multiple_choice',
                'options'        => $assocOptions,
                'correct_answer' => [$q['correct'] ?? 'A'],
                'points'         => 10,
            ]);
        }

        return $quiz;
    }

    private function createFallbackQuiz(int $lessonId): Quiz
    {
        $pool = [
            [
                'title'     => 'Password Safety Quiz',
                'questions' => [
                    ['question' => 'Which password is the strongest?', 'options' => ['123456', 'password', 'MyDog$2026!', 'qwerty'], 'correct' => 'C'],
                    ['question' => 'Should you share your password with friends?', 'options' => ['Yes', 'No', 'Only with best friends', 'Only with teachers'], 'correct' => 'B'],
                    ['question' => 'What should you do if you forget your password?', 'options' => ['Ask a parent for help', 'Never use the account again', 'Tell everyone', 'Use 123456'], 'correct' => 'A'],
                    ['question' => 'What is a good way to remember a strong password?', 'options' => ['Write it on your desk', 'Use a sentence you remember', 'Use your name', 'Use your birthday'], 'correct' => 'B'],
                    ['question' => 'A website asks for your password by email. You should…', 'options' => ['Reply with your password', 'Ignore and report it', 'Change your password', 'Tell your friends'], 'correct' => 'B'],
                ],
            ],
            [
                'title'     => 'Online Safety Quiz',
                'questions' => [
                    ['question' => 'Someone online asks for your address. You should…', 'options' => ['Tell them', 'Ignore them', 'Ask a parent', 'Both B and C'], 'correct' => 'D'],
                    ['question' => 'Is it safe to use the same password everywhere?', 'options' => ['Yes', 'No', 'Only for games', 'Only for school'], 'correct' => 'B'],
                    ['question' => 'What is phishing?', 'options' => ['A type of fish', 'A trick to steal info', 'A video game', 'A password'], 'correct' => 'B'],
                    ['question' => 'Who should you tell if you see something strange online?', 'options' => ['No one', 'Your friends', 'A trusted adult', 'The person online'], 'correct' => 'C'],
                    ['question' => 'What is a strong password?', 'options' => ['Your name', '123456', 'Mix of letters, numbers, symbols', 'Your birthday'], 'correct' => 'C'],
                ],
            ],
        ];

        $usedTitles = Quiz::where('lesson_id', $lessonId)->pluck('title')->all();
        $available  = array_filter($pool, fn($q) => !in_array($q['title'], $usedTitles));
        $chosen     = $available ? $available[array_key_first($available)] : $pool[0];

        if (!$available) {
            $chosen['title'] .= ' ' . now()->format('YmdHis');
        }

        $quiz = Quiz::create([
            'lesson_id'          => $lessonId,
            'title'              => $chosen['title'],
            'passing_score'      => 70,
            'time_limit_minutes' => 10,
            'max_attempts'       => 3,
            'is_published'       => false,
        ]);

        $optionLabels = ['A', 'B', 'C', 'D'];
        foreach ($chosen['questions'] as $q) {
            $assocOptions = [];
            foreach ($q['options'] as $i => $opt) {
                $assocOptions[$optionLabels[$i]] = $opt;
            }
            $quiz->questions()->create([
                'question_text'  => $q['question'],
                'type'           => 'multiple_choice',
                'options'        => $assocOptions,
                'correct_answer' => [$q['correct']],
                'points'         => 10,
            ]);
        }

        return $quiz;
    }

    // ─────────────────────────────────────────────
    //  SCENARIO
    // ─────────────────────────────────────────────

    public function generateScenario(int $lessonId, string $topic): Scenario
    {
        $prompt = "Return ONLY JSON in ONE LINE. No code fences. No extra text.
Topic: $topic
Strict JSON format:
{\"title\":\"...\",\"situation\":\"...\",\"choices\":[{\"text\":\"...\",\"safety_score\":0,\"explanation\":\"...\",\"consequence\":\"...\"}]}
Keep it short. Max 3 choices. 1 sentence each.";

        $data = $this->aiService->askJson($prompt, 3);

        if (!is_array($data) || !isset($data['title'], $data['situation'])) {
            Log::warning('AI Scenario invalid, using fallback', ['response' => $data]);
            return $this->createFallbackScenario($lessonId);
        }

        $data['title']     = str_replace(['"', "'"], ['\"', "\'"], $data['title']);
        $data['situation'] = str_replace(['"', "'"], ['\"', "\'"], $data['situation']);

        if (!isset($data['choices']) || !is_array($data['choices']) || count($data['choices']) === 0) {
            Log::warning('AI Scenario missing choices, adding default choice', ['response' => $data]);
            $data['choices'] = [
                [
                    'text'        => 'No AI choices generated',
                    'safety_score'=> 0,
                    'explanation' => 'AI did not return choices',
                    'consequence' => 'Fallback consequence',
                ]
            ];
        }

        foreach ($data['choices'] as &$choice) {
            $choice['text']        = str_replace(['"', "'"], ['\"', "\'"], $choice['text'] ?? '');
            $choice['explanation'] = str_replace(['"', "'"], ['\"', "\'"], $choice['explanation'] ?? '');
            $choice['consequence'] = str_replace(['"', "'"], ['\"', "\'"], $choice['consequence'] ?? '');
        }

        $scenario = Scenario::create([
            'lesson_id'       => $lessonId,
            'title'           => $this->uniqueScenarioTitle($lessonId, $data['title']),
            'description'     => $data['situation'],
            'situation'       => $data['situation'],
            'difficulty'      => 'easy',
            'age_group'       => '10-13',
            'is_ai_generated' => true,
            'is_published'    => false,
        ]);

        foreach ($data['choices'] as $choice) {
            $scenario->choices()->create([
                'choice_text'    => $choice['text'] ?? '',
                'consequence'    => $choice['consequence'] ?? '',
                'safety_score'   => $choice['safety_score'] ?? 0,
                'ai_explanation' => $choice['explanation'] ?? '',
            ]);
        }

        return $scenario;
    }

    private function createFallbackScenario(int $lessonId): Scenario
    {
        $pool = [
            [
                'title'     => 'Phishing Email Alert!',
                'situation' => 'You receive an email saying you won a prize. It asks you to click a link and enter your password.',
                'choices'   => [
                    ['choice_text' => 'Click the link', 'consequence' => 'Risk giving your password', 'safety_score' => 0, 'ai_explanation' => 'Never enter password on suspicious links'],
                    ['choice_text' => 'Ignore and tell an adult', 'consequence' => 'Stay safe', 'safety_score' => 100, 'ai_explanation' => 'Always check with an adult'],
                    ['choice_text' => 'Reply asking for more details', 'consequence' => 'May encourage scammer', 'safety_score' => 20, 'ai_explanation' => 'Do not engage with suspicious emails'],
                ],
            ],
            [
                'title'     => 'Stranger Danger on Social Media',
                'situation' => 'A stranger sends you a friend request and asks for your school name.',
                'choices'   => [
                    ['choice_text' => 'Tell them and accept', 'consequence' => 'Risk personal info', 'safety_score' => 0, 'ai_explanation' => 'Never share personal info with strangers'],
                    ['choice_text' => 'Ignore and tell a parent', 'consequence' => 'Stay safe', 'safety_score' => 100, 'ai_explanation' => 'Check with an adult if unsure'],
                    ['choice_text' => 'Block and report user', 'consequence' => 'Prevent further contact', 'safety_score' => 100, 'ai_explanation' => 'Blocking/reporting is safest'],
                ],
            ],
        ];

        $usedTitles = Scenario::where('lesson_id', $lessonId)->pluck('title')->all();
        $available  = array_filter($pool, fn($s) => !in_array($s['title'], $usedTitles));
        $chosen     = $available ? $available[array_key_first($available)] : $pool[0];

        if (!$available) {
            $suffix           = now()->format('YmdHis');
            $chosen['title']  .= ' ' . $suffix;
            $chosen['situation'] .= ' [' . $suffix . ']';
        }

        $scenario = Scenario::create([
            'lesson_id'       => $lessonId,
            'title'           => $chosen['title'],
            'description'     => $chosen['situation'],
            'situation'       => $chosen['situation'],
            'difficulty'      => 'easy',
            'age_group'       => '10-13',
            'is_ai_generated' => false,
            'is_published'    => false,
        ]);

        foreach ($chosen['choices'] as $choice) {
            $scenario->choices()->create([
                'choice_text'    => $choice['choice_text'] ?? '',
                'consequence'    => $choice['consequence'] ?? '',
                'safety_score'   => $choice['safety_score'] ?? 0,
                'ai_explanation' => $choice['ai_explanation'] ?? '',
            ]);
        }

        return $scenario;
    }

    // ─────────────────────────────────────────────
    //  HELPERS
    // ─────────────────────────────────────────────

    private function uniqueSlug(string $title, string $model): string
    {
        $base   = Str::slug($title);
        $slug   = $base;
        $suffix = 1;
        while ($model::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $suffix++;
        }
        return $slug;
    }

    private function uniqueScenarioTitle(int $lessonId, string $title): string
    {
        $base   = $title;
        $result = $base;
        $suffix = 2;
        while (Scenario::where('lesson_id', $lessonId)->where('title', $result)->exists()) {
            $result = $base . ' #' . $suffix++;
        }
        return $result;
    }
}
