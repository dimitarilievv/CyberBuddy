<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AiContentSuggestion;
use App\Models\User;

class AiContentSuggestionSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::query()->first();

        if (! $user) {
            return;
        }

        AiContentSuggestion::create([
            'user_id' => $user->id,
            'content_type' => 'question',
            'title' => 'New phishing question suggestion',
            'suggested_content' => 'Question: Which of these emails is most likely phishing? (Provide options...)',
            'status' => 'pending',
            'admin_notes' => null,
            'reviewed_by' => null,
            'reviewed_at' => null,
        ]);

        AiContentSuggestion::create([
            'user_id' => $user->id,
            'content_type' => 'tip',
            'title' => 'Add a tip about suspicious links',
            'suggested_content' => 'Tip: Always hover over links to check the real URL before clicking.',
            'status' => 'pending',
            'admin_notes' => null,
            'reviewed_by' => null,
            'reviewed_at' => null,
        ]);
    }
}
