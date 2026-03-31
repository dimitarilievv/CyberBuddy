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
        // === PHISHING MODULE SCENARIO ===
        $phishingLesson = Lesson::where('slug', 'phishing-scenario')->first();

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
                'choice_text' => 'I report the email to my parents or teacher.',
                'consequence' => 'Excellent! You avoided the scam and helped others stay safe.',
                'safety_score' => 100,
                'is_recommended' => true,
                'ai_explanation' => 'Reporting suspicious emails is the best action.',
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // === SOCIAL MEDIA SCENARIO ===
        $socialLesson = Lesson::where('slug', 'social-scenario')->first();

        $scenario2 = Scenario::create([
            'lesson_id' => $socialLesson?->id,
            'title' => 'Unknown Friend Request',
            'description' => 'What do you do when a stranger tries to connect with you on social media?',
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

        // === CYBERBULLYING SCENARIO ===
        $bullyingLesson = Lesson::where('slug', 'bullying-scenario')->first();

        $scenario3 = Scenario::create([
            'lesson_id' => $bullyingLesson?->id,
            'title' => 'Witnessing Cyberbullying',
            'description' => 'You see someone being cyberbullied online. What do you do?',
            'situation' => 'You are on your class group chat and notice that some students are making fun of a classmate named Nikola. They are posting embarrassing photos and laughing at him. Nikola is not responding and seems upset.',
            'difficulty' => 'medium',
            'age_group' => '10-13',
            'is_published' => true,
        ]);

        ScenarioChoice::insert([
            [
                'scenario_id' => $scenario3->id,
                'choice_text' => 'I join in to be part of the group. Everyone is doing it, so it must be okay.',
                'consequence' => 'You are now part of the problem. Cyberbullying hurts people, and joining makes it worse.',
                'safety_score' => 0,
                'is_recommended' => false,
                'ai_explanation' => 'Joining in on bullying makes you a bully too. It can seriously hurt someone\'s feelings and mental health.',
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'scenario_id' => $scenario3->id,
                'choice_text' => 'I just ignore it. It\'s not my problem.',
                'consequence' => 'By doing nothing, the bullying continues. Nikola feels alone and unsupported.',
                'safety_score' => 30,
                'is_recommended' => false,
                'ai_explanation' => 'Ignoring bullying allows it to continue. Standing up for others makes a big difference.',
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'scenario_id' => $scenario3->id,
                'choice_text' => 'I send a private message to Nikola to see if he is okay and tell a trusted adult.',
                'consequence' => 'You are an upstander! Nikola feels supported, and the adult can help stop the bullying.',
                'safety_score' => 100,
                'is_recommended' => true,
                'ai_explanation' => 'Supporting the victim and reporting to adults are the best actions against cyberbullying.',
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'scenario_id' => $scenario3->id,
                'choice_text' => 'I post a message saying "This is not cool. Stop being mean."',
                'consequence' => 'Good! You stood up for Nikola. This might stop the bullying or at least show support.',
                'safety_score' => 90,
                'is_recommended' => false,
                'ai_explanation' => 'Standing up publicly is brave and helpful. Just make sure to also report to an adult.',
                'sort_order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // === FAKE NEWS SCENARIO ===
        $fakeNewsLesson = Lesson::where('slug', 'fake-news-scenario')->first();

        $scenario4 = Scenario::create([
            'lesson_id' => $fakeNewsLesson?->id,
            'title' => 'Viral News Story',
            'description' => 'A shocking news story is going viral. How do you respond?',
            'situation' => 'Your friend shares a post that says: "BREAKING: Free Robux for everyone who shares this post! Limited time only! Click here to claim your 10,000 Robux!" The post has a picture of a famous YouTuber and has been shared 50,000 times.',
            'difficulty' => 'medium',
            'age_group' => '10-13',
            'is_published' => true,
        ]);

        ScenarioChoice::insert([
            [
                'scenario_id' => $scenario4->id,
                'choice_text' => 'I immediately click the link and share it too. Free Robux sounds amazing!',
                'consequence' => 'This is a classic scam! The link leads to a fake website that could steal your account info or install malware.',
                'safety_score' => 0,
                'is_recommended' => false,
                'ai_explanation' => 'There are no legitimate "free Robux" offers. These are always scams designed to steal accounts.',
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'scenario_id' => $scenario4->id,
                'choice_text' => 'I share it too because my friend shared it. They must have checked it.',
                'consequence' => 'Sharing without checking spreads misinformation. Your friend might have been tricked too!',
                'safety_score' => 20,
                'is_recommended' => false,
                'ai_explanation' => 'Just because a friend shares something doesn\'t mean it\'s true. Always verify first.',
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'scenario_id' => $scenario4->id,
                'choice_text' => 'I check the source and search online if this is real. I find out it is fake and tell my friend.',
                'consequence' => 'Smart choice! You avoided the scam and helped your friend too.',
                'safety_score' => 100,
                'is_recommended' => true,
                'ai_explanation' => 'Always verify before sharing. Use fact-checking websites and look for official sources.',
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // === ADDITIONAL SCENARIOS FOR THE SAME LESSONS ===

        // Second scenario for Phishing module (different situation)
        $scenario5 = Scenario::create([
            'lesson_id' => $phishingLesson?->id,
            'title' => 'Text Message Scam',
            'description' => 'You receive a suspicious text message',
            'situation' => 'You get a text message: "DHL: Your package cannot be delivered. Click here to update delivery address: https://bit.ly/dhl-update" You were not expecting any package.',
            'difficulty' => 'easy',
            'age_group' => '10-13',
            'is_published' => true,
        ]);

        ScenarioChoice::insert([
            [
                'scenario_id' => $scenario5->id,
                'choice_text' => 'I click the link to see what package it is. Maybe someone sent me a gift!',
                'consequence' => 'The link leads to a fake website that tries to steal your personal information or install malware.',
                'safety_score' => 0,
                'is_recommended' => false,
                'ai_explanation' => 'If you weren\'t expecting a package, this is likely a phishing attempt. Never click links from unknown numbers.',
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'scenario_id' => $scenario5->id,
                'choice_text' => 'I ignore it and delete the message.',
                'consequence' => 'Good choice. You avoided the scam.',
                'safety_score' => 90,
                'is_recommended' => false,
                'ai_explanation' => 'Ignoring and deleting is safe, but reporting helps protect others.',
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'scenario_id' => $scenario5->id,
                'choice_text' => 'I tell my parents and report the message as spam.',
                'consequence' => 'Perfect! You avoided the scam and helped prevent others from falling for it.',
                'safety_score' => 100,
                'is_recommended' => true,
                'ai_explanation' => 'Reporting spam messages helps the platform identify and block scammers.',
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Second scenario for Social Media module
        $scenario6 = Scenario::create([
            'lesson_id' => $socialLesson?->id,
            'title' => 'Sharing Location',
            'description' => 'Should you share your location online?',
            'situation' => 'You are on vacation with your family. You want to post a photo from the beach with the caption "Having fun in Ohrid! Wish you were here!"',
            'difficulty' => 'easy',
            'age_group' => '10-13',
            'is_published' => true,
        ]);

        ScenarioChoice::insert([
            [
                'scenario_id' => $scenario6->id,
                'choice_text' => 'I post it with the location tag so everyone knows where I am.',
                'consequence' => 'Posting your location tells people that you are away from home, which could be risky.',
                'safety_score' => 30,
                'is_recommended' => false,
                'ai_explanation' => 'Never share your current location. Post vacation photos AFTER you return home.',
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'scenario_id' => $scenario6->id,
                'choice_text' => 'I post it without the location tag, but still say I am on vacation.',
                'consequence' => 'Better, but still sharing that you are away from home. Wait until you return!',
                'safety_score' => 60,
                'is_recommended' => false,
                'ai_explanation' => 'It\'s safer to post vacation photos after you get back home.',
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'scenario_id' => $scenario6->id,
                'choice_text' => 'I wait until I get home to post the photos.',
                'consequence' => 'Excellent! This keeps your location private while you are away.',
                'safety_score' => 100,
                'is_recommended' => true,
                'ai_explanation' => 'Posting after returning home is the safest way to share vacation memories.',
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Second scenario for Fake News module
        $scenario7 = Scenario::create([
            'lesson_id' => $fakeNewsLesson?->id,
            'title' => 'Health Information',
            'description' => 'Is this health advice real?',
            'situation' => 'You see a post saying: "Drinking lemon water with honey cures COVID-19! Share this to save lives!" The post has no scientific sources and is from an account called "NaturalHealing123".',
            'difficulty' => 'medium',
            'age_group' => '10-13',
            'is_published' => true,
        ]);

        ScenarioChoice::insert([
            [
                'scenario_id' => $scenario7->id,
                'choice_text' => 'I share it because it might help people.',
                'consequence' => 'You are spreading misinformation. There is no evidence that this cures COVID-19.',
                'safety_score' => 10,
                'is_recommended' => false,
                'ai_explanation' => 'Medical advice should come from official health organizations like WHO or CDC.',
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'scenario_id' => $scenario7->id,
                'choice_text' => 'I don\'t share it, but I don\'t check it either.',
                'consequence' => 'At least you didn\'t spread it, but you could learn to spot fake health news.',
                'safety_score' => 60,
                'is_recommended' => false,
                'ai_explanation' => 'It\'s good that you didn\'t share, but learning to fact-check helps you stay informed.',
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'scenario_id' => $scenario7->id,
                'choice_text' => 'I check if official health organizations have shared this information. I find it\'s fake and comment to warn others.',
                'consequence' => 'Great job! You fact-checked and helped stop misinformation.',
                'safety_score' => 100,
                'is_recommended' => true,
                'ai_explanation' => 'Always verify health information with official sources before believing or sharing.',
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->command->info('Scenarios seeded successfully!');
    }
}
