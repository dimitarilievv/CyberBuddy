<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AiSuggestionGeneratorService;

class GenerateAiContentSuggestions extends Command
{
    protected $signature = 'ai:suggestions:generate {--topics=3}';
    protected $description = 'Generate AI content suggestions (pending) based on student mistakes.';

    public function handle(AiSuggestionGeneratorService $generator): int
    {
        $topics = (int) $this->option('topics');

        $created = $generator->generateAndStoreSuggestions($topics);

        $this->info("Created {$created} AI suggestions.");

        return self::SUCCESS;
    }
}
