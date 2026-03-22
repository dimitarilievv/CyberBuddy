<?php
namespace Database\Seeders;
use App\Models\Lesson;
use App\Models\Scenario;
use App\Models\ScenarioChoice;
use Illuminate\Database\Seeder;
class ScenarioSeeder extends Seeder
{
    public function run(): void
    {
        $phishingLesson = Lesson::where('slug', 'phishing-scenario')->first();
        // Scenario 1: Suspicious Email
        $scenario1 = Scenario::create([
            'lesson_id' => $phishingLesson?->id,
            'title' => 'Suspicious Email from "Bank"',
            'description' => 'Test if you can recognize a phishing email',
            'situation' => 'You get an email from "Central Bank" that says: "Your account is blocked! Click HERE to confirm your identity within 24 hours or the account will be closed forever!" The email has the bank logo but the sender address is: security@centr4l-bank-verify.com',
            'difficulty' => 'medium',
            'age_group' => '10-13',
            'is_published' => true,
        ]);
        ScenarioChoice::insert([
            [
                'scenario_id' => $scenario1->id,
                'choice_text' => 'I click the link and enter my details right away. I do not want to lose the account!',
                'consequence' => 'You just gave your data to a hacker! They can now access your account and steal money.',
                'safety_score' => 0,
                'is_recommended' => false,
                'ai_explanation' => 'Banks never ask for personal data by email. The address "centr4l-bank-verify.com" is fake and uses a 4 instead of an a plus extra words.',
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'scenario_id' => $scenario1->id,
                'choice_text' => 'I check the email more carefully, notice the strange address, and do not click the link.',
                'consequence' => 'Nice! You noticed the address is not real. This is a classic phishing attempt.',
                'safety_score' => 80,
                'is_recommended' => false,
                'ai_explanation' => 'Good job checking the sender, but it is even better to report it.',
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'scenario_id' => $scenario1->id,
                'choice_text' => 'I do not click the link, report the email as spam, and tell my parents.',
                'consequence' => 'Excellent! You made the safest choice: do not click, report it, and ask an adult for help.',
                'safety_score' => 100,
                'is_recommended' => true,
                'ai_explanation' => 'This is the best response. Never click suspicious links, always report them, and ask an adult for help.',
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        // Scenario 2: Unknown Person on Social Media
        $scenario2 = Scenario::create([
            'lesson_id' => null,
            'title' => 'Unknown Friend on Instagram',
            'description' => 'What do you do when someone you do not know messages you on social media?',
            'situation' => 'On Instagram you get a message from a person with a teen profile photo: "Hi! I saw you in @gamingmk comments. Do you play Fortnite? I am from Bitola. Which school do you go to so I can see if we have common friends?"',
            'difficulty' => 'easy',
            'age_group' => '10-13',
            'is_published' => true,
        ]);
        ScenarioChoice::insert([
            [
                'scenario_id' => $scenario2->id,
                'choice_text' => 'I tell them which school I go to and share my friends names.',
                'consequence' => 'Dangerous! You do not know who is behind that profile. It might not be a teen at all.',
                'safety_score' => 0,
                'is_recommended' => false,
                'ai_explanation' => 'Never share personal information (school, address, phone) with strangers online.',
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'scenario_id' => $scenario2->id,
                'choice_text' => 'I do not reply and I block the profile.',
                'consequence' => 'Good choice. Blocking is a safe option.',
                'safety_score' => 80,
                'is_recommended' => false,
                'ai_explanation' => 'Blocking is good, but reporting is even better.',
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'scenario_id' => $scenario2->id,
                'choice_text' => 'I do not reply, I block, I report the profile, and I tell my parents.',
                'consequence' => 'Perfect! The safest option: block, report, and tell an adult.',
                'safety_score' => 100,
                'is_recommended' => true,
                'ai_explanation' => 'Three steps: do not reply, block, tell an adult. Always.',
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
