<?php
namespace Database\Seeders;
use App\Models\Lesson;
use App\Models\Module;
use Illuminate\Database\Seeder;
class LessonSeeder extends Seeder
{
    public function run(): void
    {
        $passwordModule = Module::where('slug', 'strong-passwords')->first();
        $lessons = [
            [
                'module_id' => $passwordModule->id,
                'title' => 'Why Passwords Matter',
                'slug' => 'why-passwords-matter',
                'content' => '<h2>Passwords are the key to your digital world</h2>
                <p>Think of a password like the key to your house. Would you leave your key under the doormat where anyone can find it?</p>
                <h3>What can happen with a weak password?</h3>
                <ul>
                    <li>Someone can steal your gaming account</li>
                    <li>Someone can read your messages</li>
                    <li>Someone can pretend to be you</li>
                    <li>Someone can buy things using your account</li>
                </ul>
                <h3>Did you know?</h3>
                <p>The password "123456" can be guessed in under a second! A password like "M0yDog3$IsGreat!" could take millions of years.</p>',
                'type' => 'text',
                'estimated_minutes' => 5,
                'sort_order' => 1,
                'is_published' => true,
            ],
            [
                'module_id' => $passwordModule->id,
                'title' => 'How to Create a Strong Password',
                'slug' => 'create-strong-password',
                'content' => '<h2>Rules for a Strong Password</h2>
                <h3>Every strong password should have:</h3>
                <ol>
                    <li>At least 12 characters</li>
                    <li>Uppercase letters (A, B, C...)</li>
                    <li>Lowercase letters (a, b, c...)</li>
                    <li>Numbers (1, 2, 3...)</li>
                    <li>Special symbols (!@#$%)</li>
                </ol>
                <h3>Memory trick: The sentence method</h3>
                <p>Pick a sentence you know and take the first letters:</p>
                <p><strong>"My dog Rex is 3 years old and loves bones!"</strong></p>
                <p>Becomes: <code>Mdr3yoalb!</code></p>
                <h3>Never use:</h3>
                <ul>
                    <li>Your name or birthday</li>
                    <li>123456 or password</li>
                    <li>The same password for everything</li>
                </ul>',
                'type' => 'interactive',
                'estimated_minutes' => 7,
                'sort_order' => 2,
                'is_published' => true,
            ],
            [
                'module_id' => $passwordModule->id,
                'title' => 'Test Your Knowledge!',
                'slug' => 'password-quiz-lesson',
                'content' => '<h2>Time for a quiz!</h2>
                <p>Let\'s see how much you learned about passwords. Answer the questions and earn a badge!</p>',
                'type' => 'text',
                'estimated_minutes' => 5,
                'sort_order' => 3,
                'is_published' => true,
            ],
        ];

        $phishingModule = Module::where('slug', 'recognize-phishing')->first();
        $phishingLessons = [
            [
                'module_id' => $phishingModule->id,
                'title' => 'What Is Phishing?',
                'slug' => 'what-is-phishing',
                'content' => '<h2>Phishing = digital fishing</h2>
                <p>Phishing is when someone tries to trick you into giving away personal information (passwords, card numbers) through fake messages or websites.</p>
                <h3>How it works</h3>
                <ol>
                    <li>You get an email or message that looks real</li>
                    <li>The message scares or tempts you ("Your account was hacked!" or "You won a phone!")</li>
                    <li>It sends you to a fake website</li>
                    <li>You type your info and they steal it</li>
                </ol>',
                'type' => 'text',
                'estimated_minutes' => 5,
                'sort_order' => 1,
                'is_published' => true,
            ],
            [
                'module_id' => $phishingModule->id,
                'title' => 'Scenario: Suspicious Email',
                'slug' => 'phishing-scenario',
                'content' => '<h2>Scenario: You receive a strange email</h2>
                <p>Play this scenario and decide what to do!</p>',
                'type' => 'scenario',
                'estimated_minutes' => 10,
                'sort_order' => 2,
                'is_published' => true,
            ],
        ];
        $deviceModule = Module::where('slug', 'device-security')->first();
        $deviceLessons = [
            [
                'module_id' => $deviceModule->id,
                'title' => 'Why Updates Matter',
                'slug' => 'why-updates-matter',
                'content' => '<h2>Why Should You Update Your Device?</h2><p>Updates fix bugs and protect your device from hackers. Always install updates when you see them!</p>',
                'type' => 'text',
                'estimated_minutes' => 5,
                'sort_order' => 1,
                'is_published' => true,
            ],
            [
                'module_id' => $deviceModule->id,
                'title' => 'Lock Your Device',
                'slug' => 'lock-your-device',
                'content' => '<h2>Locking Your Device</h2><p>Always use a PIN, password, or fingerprint to lock your phone or tablet. This keeps your information safe if you lose your device.</p>',
                'type' => 'text',
                'estimated_minutes' => 5,
                'sort_order' => 2,
                'is_published' => true,
            ],
            [
                'module_id' => $deviceModule->id,
                'title' => 'Safe App Downloads',
                'slug' => 'safe-app-downloads',
                'content' => '<h2>Downloading Apps Safely</h2><p>Only download apps from official stores like Google Play or the App Store. Avoid apps that ask for too many permissions.</p>',
                'type' => 'text',
                'estimated_minutes' => 5,
                'sort_order' => 3,
                'is_published' => true,
            ],
        ];
        $privacyModule = Module::where('slug', 'privacy-settings')->first();
        $privacyLessons = [
            [
                'module_id' => $privacyModule->id,
                'title' => 'What is Privacy?',
                'slug' => 'what-is-privacy',
                'content' => '<h2>What is Privacy?</h2><p>Privacy means keeping your personal information safe from others. Learn how to control what you share online.</p>',
                'type' => 'text',
                'estimated_minutes' => 5,
                'sort_order' => 1,
                'is_published' => true,
            ],
            [
                'module_id' => $privacyModule->id,
                'title' => 'Privacy on Social Media',
                'slug' => 'privacy-on-social-media',
                'content' => '<h2>Privacy on Social Media</h2><p>Set your accounts to private and only accept friend requests from people you know.</p>',
                'type' => 'text',
                'estimated_minutes' => 5,
                'sort_order' => 2,
                'is_published' => true,
            ],
        ];
        $footprintModule = Module::where('slug', 'digital-footprint')->first();
        $footprintLessons = [
            [
                'module_id' => $footprintModule->id,
                'title' => 'What is a Digital Footprint?',
                'slug' => 'what-is-digital-footprint',
                'content' => '<h2>Your Digital Footprint</h2><p>Everything you post, like, or share online leaves a trace. Think before you post!</p>',
                'type' => 'text',
                'estimated_minutes' => 5,
                'sort_order' => 1,
                'is_published' => true,
            ],
            [
                'module_id' => $footprintModule->id,
                'title' => 'Cleaning Up Your Footprint',
                'slug' => 'clean-up-footprint',
                'content' => '<h2>How to Clean Up Your Digital Footprint</h2><p>Delete old posts, remove unused accounts, and check your privacy settings regularly.</p>',
                'type' => 'text',
                'estimated_minutes' => 5,
                'sort_order' => 2,
                'is_published' => true,
            ],
        ];
        $fakeNewsModule = Module::where('slug', 'fake-news-detection')->first();
        $fakeNewsLessons = [
            [
                'module_id' => $fakeNewsModule->id,
                'title' => 'Spotting Fake News',
                'slug' => 'spotting-fake-news',
                'content' => '<h2>How to Spot Fake News</h2><p>Check the source, look for other reports, and ask an adult if you are unsure.</p>',
                'type' => 'text',
                'estimated_minutes' => 5,
                'sort_order' => 1,
                'is_published' => true,
            ],
            [
                'module_id' => $fakeNewsModule->id,
                'title' => 'Fact-Checking Online',
                'slug' => 'fact-checking-online',
                'content' => '<h2>Fact-Checking</h2><p>Use fact-checking websites and tools to verify information before sharing it.</p>',
                'type' => 'text',
                'estimated_minutes' => 5,
                'sort_order' => 2,
                'is_published' => true,
            ],
        ];
        foreach (array_merge($lessons, $phishingLessons, $deviceLessons, $privacyLessons, $footprintLessons, $fakeNewsLessons) as $lesson) {
            Lesson::updateOrCreate(
                [
                    'slug' => $lesson['slug']
                ],
                $lesson
            );
        }
    }
}
