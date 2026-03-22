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
        // Lessons for the "Recognize Phishing" module
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
        foreach (array_merge($lessons, $phishingLessons) as $lesson) {
            Lesson::create($lesson);
        }
    }
}
