<?php
namespace Database\Seeders;

use App\Models\Lesson;
use App\Models\Module;
use Illuminate\Database\Seeder;

class LessonSeeder extends Seeder
{
    public function run(): void
    {
        // === PASSWORDS MODULE ===
        $passwordModule = Module::where('slug', 'strong-passwords')->first();
        if ($passwordModule) {
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
                    'title' => 'Password Managers: Your Digital Assistant',
                    'slug' => 'password-managers',
                    'content' => '<h2>What is a Password Manager?</h2>
                    <p>A password manager is a safe app that remembers all your passwords for you. You only need to remember ONE master password!</p>
                    <h3>Benefits:</h3>
                    <ul>
                        <li>Creates strong passwords automatically</li>
                        <li>Remembers them for you</li>
                        <li>Works across your devices</li>
                        <li>Keeps your information encrypted</li>
                    </ul>',
                    'type' => 'text',
                    'estimated_minutes' => 6,
                    'sort_order' => 3,
                    'is_published' => true,
                ],
                [
                    'module_id' => $passwordModule->id,
                    'title' => 'Test Your Knowledge!',
                    'slug' => 'password-quiz-lesson',
                    'content' => '<h2>Time for a quiz!</h2>
                    <p>Let\'s see how much you learned about passwords. Answer the questions and earn a badge!</p>',
                    'type' => 'interactive', // Changed from 'quiz' to 'interactive'
                    'estimated_minutes' => 5,
                    'sort_order' => 4,
                    'is_published' => true,
                ],
            ];
            foreach ($lessons as $lesson) {
                Lesson::updateOrCreate(['slug' => $lesson['slug']], $lesson);
            }
        }

        // === PHISHING MODULE ===
        $phishingModule = Module::where('slug', 'recognize-phishing')->first();
        if ($phishingModule) {
            $lessons = [
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
                    'title' => 'Red Flags: How to Spot Phishing',
                    'slug' => 'phishing-red-flags',
                    'content' => '<h2>Warning Signs of Phishing</h2>
                    <ul>
                        <li><strong>Urgent language:</strong> "Act now or your account will be closed!"</li>
                        <li><strong>Spelling mistakes:</strong> Real companies check their spelling</li>
                        <li><strong>Strange email address:</strong> Check if it comes from a real company domain</li>
                        <li><strong>Requests for personal info:</strong> Real companies won\'t ask for passwords by email</li>
                        <li><strong>Suspicious links:</strong> Hover over links to see where they really go</li>
                    </ul>',
                    'type' => 'text',
                    'estimated_minutes' => 6,
                    'sort_order' => 2,
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
                    'sort_order' => 3,
                    'is_published' => true,
                ],
                [
                    'module_id' => $phishingModule->id,
                    'title' => 'What to Do If You Get Phished',
                    'slug' => 'what-to-do-phished',
                    'content' => '<h2>Oops! You clicked a bad link. Now what?</h2>
                    <ol>
                        <li><strong>Tell a trusted adult immediately</strong> - Don\'t be embarrassed!</li>
                        <li><strong>Change your passwords</strong> - Especially for important accounts</li>
                        <li><strong>Enable 2-factor authentication</strong> - Add extra security</li>
                        <li><strong>Report it</strong> - Tell the company being impersonated</li>
                    </ol>',
                    'type' => 'text',
                    'estimated_minutes' => 5,
                    'sort_order' => 4,
                    'is_published' => true,
                ],
            ];
            foreach ($lessons as $lesson) {
                Lesson::updateOrCreate(['slug' => $lesson['slug']], $lesson);
            }
        }

        // === SOCIAL MEDIA SAFETY MODULE ===
        $socialModule = Module::where('slug', 'social-media-safety')->first();
        if ($socialModule) {
            $lessons = [
                [
                    'module_id' => $socialModule->id,
                    'title' => 'Privacy Settings on Social Media',
                    'slug' => 'social-privacy-settings',
                    'content' => '<h2>Protect Your Social Media Accounts</h2>
                    <h3>Must-do settings:</h3>
                    <ul>
                        <li>Set your profile to <strong>PRIVATE</strong></li>
                        <li>Only accept friend requests from people you know in real life</li>
                        <li>Turn off location sharing</li>
                        <li>Review tagged photos before they appear on your profile</li>
                        <li>Don\'t share personal info like your school or phone number</li>
                    </ul>',
                    'type' => 'text',
                    'estimated_minutes' => 6,
                    'sort_order' => 1,
                    'is_published' => true,
                ],
                [
                    'module_id' => $socialModule->id,
                    'title' => 'Oversharing: What Not to Post',
                    'slug' => 'oversharing',
                    'content' => '<h2>Think Before You Post!</h2>
                    <p><strong>NEVER share:</strong></p>
                    <ul>
                        <li>Your home address or school name</li>
                        <li>Your exact location</li>
                        <li>Your phone number</li>
                        <li>Photos in your school uniform</li>
                        <li>Your daily routine (when you\'re home alone)</li>
                        <li>Anything embarrassing that you might regret later</li>
                    </ul>
                    <p>Remember: Once something is online, it can be screenshotted and shared forever!</p>',
                    'type' => 'text',
                    'estimated_minutes' => 5,
                    'sort_order' => 2,
                    'is_published' => true,
                ],
                [
                    'module_id' => $socialModule->id,
                    'title' => 'Scenario: Friend Request from a Stranger',
                    'slug' => 'social-scenario',
                    'content' => '<h2>Scenario: You get a friend request from someone you don\'t know</h2>
                    <p>They have mutual friends and seem nice. What do you do?</p>',
                    'type' => 'scenario',
                    'estimated_minutes' => 8,
                    'sort_order' => 3,
                    'is_published' => true,
                ],
            ];
            foreach ($lessons as $lesson) {
                Lesson::updateOrCreate(['slug' => $lesson['slug']], $lesson);
            }
        }

        // === CYBERBULLYING MODULE ===
        $bullyingModule = Module::where('slug', 'stop-cyberbullying')->first();
        if ($bullyingModule) {
            $lessons = [
                [
                    'module_id' => $bullyingModule->id,
                    'title' => 'What is Cyberbullying?',
                    'slug' => 'what-is-cyberbullying',
                    'content' => '<h2>Cyberbullying is bullying that happens online</h2>
                    <p>It can include:</p>
                    <ul>
                        <li>Mean texts, messages, or comments</li>
                        <li>Rumors spread online</li>
                        <li>Embarrassing photos or videos shared without permission</li>
                        <li>Being left out of groups on purpose</li>
                        <li>Fake accounts made to hurt someone</li>
                    </ul>',
                    'type' => 'text',
                    'estimated_minutes' => 5,
                    'sort_order' => 1,
                    'is_published' => true,
                ],
                [
                    'module_id' => $bullyingModule->id,
                    'title' => 'What to Do If You\'re Bullied',
                    'slug' => 'if-bullied',
                    'content' => '<h2>You are NOT alone!</h2>
                    <h3>Steps to take:</h3>
                    <ol>
                        <li><strong>DON\'T RESPOND</strong> - Bullies want a reaction</li>
                        <li><strong>SAVE THE EVIDENCE</strong> - Take screenshots</li>
                        <li><strong>BLOCK THE PERSON</strong> - Stop them from contacting you</li>
                        <li><strong>REPORT IT</strong> - Use the platform\'s report button</li>
                        <li><strong>TELL A TRUSTED ADULT</strong> - Parent, teacher, or school counselor</li>
                    </ol>',
                    'type' => 'text',
                    'estimated_minutes' => 6,
                    'sort_order' => 2,
                    'is_published' => true,
                ],
                [
                    'module_id' => $bullyingModule->id,
                    'title' => 'Be an Upstander',
                    'slug' => 'be-upstander',
                    'content' => '<h2>Don\'t be a bystander - be an upstander!</h2>
                    <p>If you see cyberbullying happening to someone else:</p>
                    <ul>
                        <li>Don\'t like or share mean posts</li>
                        <li>Send a kind message to the person being bullied</li>
                        <li>Report the bullying to adults or the platform</li>
                        <li>Stand up for others - "Hey, that\'s not cool!"</li>
                    </ul>',
                    'type' => 'text',
                    'estimated_minutes' => 5,
                    'sort_order' => 3,
                    'is_published' => true,
                ],
                [
                    'module_id' => $bullyingModule->id,
                    'title' => 'Scenario: You See Someone Being Bullied',
                    'slug' => 'bullying-scenario',
                    'content' => '<h2>Scenario: A classmate is getting mean comments on their post</h2>
                    <p>What would you do?</p>',
                    'type' => 'scenario',
                    'estimated_minutes' => 8,
                    'sort_order' => 4,
                    'is_published' => true,
                ],
            ];
            foreach ($lessons as $lesson) {
                Lesson::updateOrCreate(['slug' => $lesson['slug']], $lesson);
            }
        }

        // === GAMING SAFETY MODULE ===
        $gamingModule = Module::where('slug', 'safe-gaming')->first();
        if ($gamingModule) {
            $lessons = [
                [
                    'module_id' => $gamingModule->id,
                    'title' => 'Stay Safe While Gaming',
                    'slug' => 'safe-gaming-tips',
                    'content' => '<h2>Gaming Safety Tips</h2>
                    <ul>
                        <li>Use a nickname, not your real name</li>
                        <li>Never share your age or location</li>
                        <li>Don\'t click on links from strangers in chat</li>
                        <li>Keep your gaming accounts secure with strong passwords</li>
                        <li>Never download "free cheats" or mods from unknown sites</li>
                    </ul>',
                    'type' => 'text',
                    'estimated_minutes' => 5,
                    'sort_order' => 1,
                    'is_published' => true,
                ],
                [
                    'module_id' => $gamingModule->id,
                    'title' => 'Toxic Players and Griefers',
                    'slug' => 'toxic-players',
                    'content' => '<h2>Dealing with Toxic Players</h2>
                    <p>If someone is being mean in a game:</p>
                    <ul>
                        <li><strong>Mute them</strong> - Most games let you block voice and text chat</li>
                        <li><strong>Don\'t argue back</strong> - They want to upset you</li>
                        <li><strong>Report them</strong> - Use the game\'s report system</li>
                        <li><strong>Take a break</strong> - Step away if you\'re frustrated</li>
                    </ul>',
                    'type' => 'text',
                    'estimated_minutes' => 5,
                    'sort_order' => 2,
                    'is_published' => true,
                ],
                [
                    'module_id' => $gamingModule->id,
                    'title' => 'In-Game Purchases and Scams',
                    'slug' => 'gaming-purchases',
                    'content' => '<h2>Watch Out for Gaming Scams!</h2>
                    <p>Common gaming scams:</p>
                    <ul>
                        <li>"Free V-Bucks/Fortnite skins" - Always fake!</li>
                        <li>People asking for your account password</li>
                        <li>"I\'ll give you rare items if you click this link"</li>
                        <li>Fake giveaways on Twitch/YouTube</li>
                    </ul>
                    <p>Never share account info, even if someone promises you free stuff!</p>',
                    'type' => 'text',
                    'estimated_minutes' => 6,
                    'sort_order' => 3,
                    'is_published' => true,
                ],
            ];
            foreach ($lessons as $lesson) {
                Lesson::updateOrCreate(['slug' => $lesson['slug']], $lesson);
            }
        }

        // === PARENT GUIDE MODULE ===
        $parentModule = Module::where('slug', 'parent-guide-online-safety')->first();
        if ($parentModule) {
            $lessons = [
                [
                    'module_id' => $parentModule->id,
                    'title' => 'Starting the Conversation',
                    'slug' => 'starting-conversation',
                    'content' => '<h2>How to Talk to Your Kids About Online Safety</h2>
                    <ul>
                        <li>Start early and keep talking</li>
                        <li>Be curious, not judgmental</li>
                        <li>Learn about their favorite apps together</li>
                        <li>Create family tech agreements</li>
                        <li>Make it a safe space to share problems</li>
                    </ul>',
                    'type' => 'text',
                    'estimated_minutes' => 8,
                    'sort_order' => 1,
                    'is_published' => true,
                ],
                [
                    'module_id' => $parentModule->id,
                    'title' => 'Parental Control Tools',
                    'slug' => 'parental-control-tools',
                    'content' => '<h2>Tools to Keep Kids Safe</h2>
                    <ul>
                        <li>Screen time limits on iOS/Android</li>
                        <li>Content filters and safe search</li>
                        <li>Family Link (Google) and Screen Time (Apple)</li>
                        <li>Monitoring apps and when to use them</li>
                        <li>Gaming console parental controls</li>
                    </ul>',
                    'type' => 'text',
                    'estimated_minutes' => 10,
                    'sort_order' => 2,
                    'is_published' => true,
                ],
            ];
            foreach ($lessons as $lesson) {
                Lesson::updateOrCreate(['slug' => $lesson['slug']], $lesson);
            }
        }

        // === DEVICE SECURITY MODULE ===
        $deviceModule = Module::where('slug', 'device-security')->first();
        if ($deviceModule) {
            $lessons = [
                [
                    'module_id' => $deviceModule->id,
                    'title' => 'Why Updates Matter',
                    'slug' => 'why-updates-matter',
                    'content' => '<h2>Why Should You Update Your Device?</h2>
                    <p>Updates fix bugs and protect your device from hackers. Always install updates when you see them!</p>
                    <h3>Types of updates:</h3>
                    <ul>
                        <li>Operating system updates (iOS, Android, Windows)</li>
                        <li>App updates</li>
                        <li>Security patches</li>
                    </ul>',
                    'type' => 'text',
                    'estimated_minutes' => 5,
                    'sort_order' => 1,
                    'is_published' => true,
                ],
                [
                    'module_id' => $deviceModule->id,
                    'title' => 'Lock Your Device',
                    'slug' => 'lock-your-device',
                    'content' => '<h2>Locking Your Device</h2>
                    <p>Always use a PIN, password, or fingerprint to lock your phone or tablet. This keeps your information safe if you lose your device.</p>
                    <h3>Best practices:</h3>
                    <ul>
                        <li>Use a 6-digit PIN or longer password</li>
                        <li>Enable auto-lock after 30 seconds</li>
                        <li>Use biometrics (fingerprint/face ID) for convenience</li>
                        <li>Never share your lock code with friends</li>
                    </ul>',
                    'type' => 'text',
                    'estimated_minutes' => 5,
                    'sort_order' => 2,
                    'is_published' => true,
                ],
                [
                    'module_id' => $deviceModule->id,
                    'title' => 'Safe App Downloads',
                    'slug' => 'safe-app-downloads',
                    'content' => '<h2>Downloading Apps Safely</h2>
                    <p>Only download apps from official stores like Google Play or the App Store. Avoid apps that ask for too many permissions.</p>
                    <h3>Red flags:</h3>
                    <ul>
                        <li>Apps with very few downloads but many permissions</li>
                        <li>Requests to access contacts/photos for no reason</li>
                        <li>Apps that promise "free" paid features</li>
                    </ul>',
                    'type' => 'text',
                    'estimated_minutes' => 5,
                    'sort_order' => 3,
                    'is_published' => true,
                ],
                [
                    'module_id' => $deviceModule->id,
                    'title' => 'Public Wi-Fi Dangers',
                    'slug' => 'public-wifi-dangers',
                    'content' => '<h2>Staying Safe on Public Wi-Fi</h2>
                    <p>Public Wi-Fi (cafes, airports, schools) can be risky!</p>
                    <ul>
                        <li>Don\'t do banking or shopping on public Wi-Fi</li>
                        <li>Use a VPN (Virtual Private Network) if you need to</li>
                        <li>Turn off auto-connect to Wi-Fi networks</li>
                        <li>Forget the network after using it</li>
                    </ul>',
                    'type' => 'text',
                    'estimated_minutes' => 6,
                    'sort_order' => 4,
                    'is_published' => true,
                ],
            ];
            foreach ($lessons as $lesson) {
                Lesson::updateOrCreate(['slug' => $lesson['slug']], $lesson);
            }
        }

        // === PRIVACY SETTINGS MODULE ===
        $privacyModule = Module::where('slug', 'privacy-settings')->first();
        if ($privacyModule) {
            $lessons = [
                [
                    'module_id' => $privacyModule->id,
                    'title' => 'What is Privacy?',
                    'slug' => 'what-is-privacy',
                    'content' => '<h2>What is Privacy?</h2>
                    <p>Privacy means keeping your personal information safe from others. Learn how to control what you share online.</p>
                    <h3>Personal information includes:</h3>
                    <ul>
                        <li>Full name and birthdate</li>
                        <li>Home address and phone number</li>
                        <li>School name and location</li>
                        <li>Photos and videos of you</li>
                        <li>Passwords and account details</li>
                    </ul>',
                    'type' => 'text',
                    'estimated_minutes' => 5,
                    'sort_order' => 1,
                    'is_published' => true,
                ],
                [
                    'module_id' => $privacyModule->id,
                    'title' => 'Privacy on Social Media',
                    'slug' => 'privacy-on-social-media',
                    'content' => '<h2>Privacy on Social Media</h2>
                    <p>Set your accounts to private and only accept friend requests from people you know.</p>
                    <h3>Check these settings:</h3>
                    <ul>
                        <li>Who can see your posts?</li>
                        <li>Who can comment on your posts?</li>
                        <li>Who can send you friend requests?</li>
                        <li>Can search engines find your profile?</li>
                    </ul>',
                    'type' => 'text',
                    'estimated_minutes' => 6,
                    'sort_order' => 2,
                    'is_published' => true,
                ],
                [
                    'module_id' => $privacyModule->id,
                    'title' => 'App Permissions',
                    'slug' => 'app-permissions',
                    'content' => '<h2>App Permissions: What Apps Can See</h2>
                    <p>When you download an app, it asks for permissions. Be careful!</p>
                    <ul>
                        <li>Does a calculator app need your location? NO!</li>
                        <li>Does a game need your contacts? Probably not!</li>
                        <li>Does a photo app need your camera? YES, that makes sense!</li>
                    </ul>
                    <p>Always ask: "Why does this app need access to this?"</p>',
                    'type' => 'text',
                    'estimated_minutes' => 5,
                    'sort_order' => 3,
                    'is_published' => true,
                ],
            ];
            foreach ($lessons as $lesson) {
                Lesson::updateOrCreate(['slug' => $lesson['slug']], $lesson);
            }
        }

        // === DIGITAL FOOTPRINT MODULE ===
        $footprintModule = Module::where('slug', 'digital-footprint')->first();
        if ($footprintModule) {
            $lessons = [
                [
                    'module_id' => $footprintModule->id,
                    'title' => 'What is a Digital Footprint?',
                    'slug' => 'what-is-digital-footprint',
                    'content' => '<h2>Your Digital Footprint</h2>
                    <p>Everything you post, like, or share online leaves a trace. Think before you post!</p>
                    <h3>What creates your footprint:</h3>
                    <ul>
                        <li>Posts, photos, and videos you share</li>
                        <li>Comments and likes</li>
                        <li>Websites you visit</li>
                        <li>What others post about you</li>
                        <li>Your search history</li>
                    </ul>',
                    'type' => 'text',
                    'estimated_minutes' => 6,
                    'sort_order' => 1,
                    'is_published' => true,
                ],
                [
                    'module_id' => $footprintModule->id,
                    'title' => 'Cleaning Up Your Footprint',
                    'slug' => 'clean-up-footprint',
                    'content' => '<h2>How to Clean Up Your Digital Footprint</h2>
                    <ul>
                        <li>Delete old posts that are embarrassing or outdated</li>
                        <li>Remove unused accounts from old apps</li>
                        <li>Check privacy settings regularly</li>
                        <li>Google yourself to see what others see</li>
                        <li>Ask friends to remove unflattering posts</li>
                    </ul>',
                    'type' => 'text',
                    'estimated_minutes' => 5,
                    'sort_order' => 2,
                    'is_published' => true,
                ],
                [
                    'module_id' => $footprintModule->id,
                    'title' => 'Your Future Self Will Thank You',
                    'slug' => 'future-self',
                    'content' => '<h2>How Your Footprint Affects Your Future</h2>
                    <p>Colleges and employers often check social media!</p>
                    <ul>
                        <li>A positive footprint can help you get opportunities</li>
                        <li>A negative footprint can hurt your chances</li>
                        <li>Show your best self online</li>
                        <li>Post things you\'re proud of</li>
                    </ul>',
                    'type' => 'text',
                    'estimated_minutes' => 5,
                    'sort_order' => 3,
                    'is_published' => true,
                ],
            ];
            foreach ($lessons as $lesson) {
                Lesson::updateOrCreate(['slug' => $lesson['slug']], $lesson);
            }
        }

        // === FAKE NEWS DETECTION MODULE ===
        $fakeNewsModule = Module::where('slug', 'fake-news-detection')->first();
        if ($fakeNewsModule) {
            $lessons = [
                [
                    'module_id' => $fakeNewsModule->id,
                    'title' => 'Spotting Fake News',
                    'slug' => 'spotting-fake-news',
                    'content' => '<h2>How to Spot Fake News</h2>
                    <h3>The 5 W\'s of Fact-Checking:</h3>
                    <ul>
                        <li><strong>Who?</strong> Who wrote it? Are they reliable?</li>
                        <li><strong>What?</strong> What\'s the evidence?</li>
                        <li><strong>When?</strong> Is it recent or old news being reshared?</li>
                        <li><strong>Where?</strong> Where was it published? Is it a real news site?</li>
                        <li><strong>Why?</strong> Why was it created? To inform, entertain, or trick you?</li>
                    </ul>',
                    'type' => 'text',
                    'estimated_minutes' => 6,
                    'sort_order' => 1,
                    'is_published' => true,
                ],
                [
                    'module_id' => $fakeNewsModule->id,
                    'title' => 'Fact-Checking Online',
                    'slug' => 'fact-checking-online',
                    'content' => '<h2>Fact-Checking Tools</h2>
                    <p>Use these tools to verify information before sharing it:</p>
                    <ul>
                        <li>Google reverse image search (check if photos are real)</li>
                        <li>Snopes.com - fact-checking website</li>
                        <li>Look for other news sources covering the same story</li>
                        <li>Check if it\'s satire (The Onion, etc.)</li>
                        <li>Ask a trusted adult or teacher</li>
                    </ul>',
                    'type' => 'text',
                    'estimated_minutes' => 5,
                    'sort_order' => 2,
                    'is_published' => true,
                ],
                [
                    'module_id' => $fakeNewsModule->id,
                    'title' => 'Scenario: Viral Post',
                    'slug' => 'fake-news-scenario',
                    'content' => '<h2>Scenario: A shocking post is going viral</h2>
                    <p>Your friends are sharing it. What should you do before sharing it yourself?</p>',
                    'type' => 'scenario',
                    'estimated_minutes' => 8,
                    'sort_order' => 3,
                    'is_published' => true,
                ],
            ];
            foreach ($lessons as $lesson) {
                Lesson::updateOrCreate(['slug' => $lesson['slug']], $lesson);
            }
        }
    }
}
