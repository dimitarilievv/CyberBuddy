<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ReportedContent;
use App\Models\User;

class ReportedContentSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::query()->first();

        if (! $user) {
            return;
        }

        ReportedContent::create([
            'reporter_id' => $user->id,
            'reportable_type' => 'App\\Models\\Question',
            'reportable_id' => 1,
            'reason' => 'inappropriate', // must match enum
            'description' => 'This question seems confusing or inappropriate for kids.',
            'status' => 'pending',
            'reviewed_by' => null,
            'admin_notes' => null,
            'reviewed_at' => null,
        ]);
    }
}
