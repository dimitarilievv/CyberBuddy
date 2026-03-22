<?php
namespace Database\Seeders;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
class UserSeeder extends Seeder
{
    public function run(): void
    {
        // === ADMIN ===
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@cyberbuddy.mk',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('admin');
        UserProfile::create([
            'user_id' => $admin->id,
            'language' => 'en',
        ]);
        // === TEACHER ===
        $teacher = User::create([
            'name' => 'Maria Petrova',
            'email' => 'teacher@cyberbuddy.mk',
            'password' => Hash::make('password123'),
            'role' => 'teacher',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $teacher->assignRole('teacher');
        UserProfile::create([
            'user_id' => $teacher->id,
            'school' => 'OU Kiril and Metodij',
            'language' => 'en',
        ]);
        // === PARENT ===
        $parent = User::create([
            'name' => 'Ivan Stojanov',
            'email' => 'parent@cyberbuddy.mk',
            'password' => Hash::make('password123'),
            'role' => 'parent',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $parent->assignRole('parent');
        UserProfile::create([
            'user_id' => $parent->id,
            'language' => 'en',
        ]);
        // === CHILDREN ===
        $child1 = User::create([
            'name' => 'Ana Stojanova',
            'email' => 'ana@cyberbuddy.mk',
            'password' => Hash::make('password123'),
            'role' => 'child',
            'date_of_birth' => '2013-05-15',
            'parent_id' => $parent->id,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $child1->assignRole('child');
        UserProfile::create([
            'user_id' => $child1->id,
            'school' => 'OU Kiril and Metodij',
            'grade' => '6',
            'language' => 'en',
            'interests' => ['gaming', 'social_media', 'videos'],
        ]);
        $child2 = User::create([
            'name' => 'Marko Stojanov',
            'email' => 'marko@cyberbuddy.mk',
            'password' => Hash::make('password123'),
            'role' => 'child',
            'date_of_birth' => '2015-09-20',
            'parent_id' => $parent->id,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $child2->assignRole('child');
        UserProfile::create([
            'user_id' => $child2->id,
            'school' => 'OU Kiril and Metodij',
            'grade' => '4',
            'language' => 'en',
            'interests' => ['gaming', 'youtube'],
        ]);
        $child3 = User::create([
            'name' => 'Elena Nikolova',
            'email' => 'elena@cyberbuddy.mk',
            'password' => Hash::make('password123'),
            'role' => 'child',
            'date_of_birth' => '2012-03-10',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $child3->assignRole('child');
        UserProfile::create([
            'user_id' => $child3->id,
            'school' => 'OU Goce Delchev',
            'grade' => '7',
            'language' => 'en',
            'interests' => ['social_media', 'music', 'art'],
        ]);
    }
}
