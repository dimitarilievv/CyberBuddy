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
        // === TEACHERS ===
        $teachers = [
            [
                'name' => 'Marija Petrova',
                'email' => 'teacher_marija@cyberbuddy.mk',
                'school' => 'OU Kiril and Metodij',
            ],
            [
                'name' => 'Darko Nikolovski',
                'email' => 'teacher_darko@cyberbuddy.mk',
                'school' => 'OU Goce Delchev',
            ],
            [
                'name' => 'Elena Stojanova',
                'email' => 'teacher_elena@cyberbuddy.mk',
                'school' => 'OU Kiril and Metodij',
            ],
            [
                'name' => 'Petar Trajkov',
                'email' => 'teacher_petar@cyberbuddy.mk',
                'school' => 'OU Braka Miladinovci',
            ],
        ];

        foreach ($teachers as $teacherData) {
            $teacher = User::create([
                'name' => $teacherData['name'],
                'email' => $teacherData['email'],
                'password' => Hash::make('password123'),
                'role' => 'teacher',
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
            $teacher->assignRole('teacher');
            UserProfile::create([
                'user_id' => $teacher->id,
                'school' => $teacherData['school'],
                'language' => 'en',
            ]);
        }

        // === PARENTS ===
        $parents = [
            [
                'name' => 'Ivan Stojanov',
                'email' => 'parent_ivan@cyberbuddy.mk',
                'language' => 'en',
            ],
            [
                'name' => 'Marija Nikolova',
                'email' => 'parent_marija@cyberbuddy.mk',
                'language' => 'mk',
            ],
            [
                'name' => 'Stefan Trajkovski',
                'email' => 'parent_stefan@cyberbuddy.mk',
                'language' => 'en',
            ],
            [
                'name' => 'Ana Petrovska',
                'email' => 'parent_ana@cyberbuddy.mk',
                'language' => 'mk',
            ],
            [
                'name' => 'Vladimir Dimitrov',
                'email' => 'parent_vladimir@cyberbuddy.mk',
                'language' => 'en',
            ],
        ];

        $parentUsers = [];
        foreach ($parents as $parentData) {
            $parent = User::create([
                'name' => $parentData['name'],
                'email' => $parentData['email'],
                'password' => Hash::make('password123'),
                'role' => 'parent',
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
            $parent->assignRole('parent');
            UserProfile::create([
                'user_id' => $parent->id,
                'language' => $parentData['language'],
            ]);
            $parentUsers[] = $parent;
        }
        // === CHILDREN ===
        $children = [
            // Parent 1 (Ivan Stojanov) - 2 children
            [
                'name' => 'Ana Stojanova',
                'email' => 'ana@cyberbuddy.mk',
                'date_of_birth' => '2013-05-15',
                'parent_id' => $parentUsers[0]->id,
                'school' => 'OU Kiril and Metodij',
                'grade' => '6',
                'interests' => ['gaming', 'social_media', 'videos'],
            ],
            [
                'name' => 'Marko Stojanov',
                'email' => 'marko@cyberbuddy.mk',
                'date_of_birth' => '2015-09-20',
                'parent_id' => $parentUsers[0]->id,
                'school' => 'OU Kiril and Metodij',
                'grade' => '4',
                'interests' => ['gaming', 'youtube'],
            ],

            // Parent 2 (Marija Nikolova) - 3 children
            [
                'name' => 'Tea Nikolova',
                'email' => 'tea.nikolova@cyberbuddy.mk',
                'date_of_birth' => '2014-02-28',
                'parent_id' => $parentUsers[1]->id,
                'school' => 'OU Goce Delchev',
                'grade' => '5',
                'interests' => ['dancing', 'music', 'social_media'],
            ],
            [
                'name' => 'Luka Nikolov',
                'email' => 'luka.nikolov@cyberbuddy.mk',
                'date_of_birth' => '2016-07-12',
                'parent_id' => $parentUsers[1]->id,
                'school' => 'OU Goce Delchev',
                'grade' => '3',
                'interests' => ['gaming', 'sports', 'youtube'],
            ],
            [
                'name' => 'Mia Nikolova',
                'email' => 'mia.nikolova@cyberbuddy.mk',
                'date_of_birth' => '2018-11-05',
                'parent_id' => $parentUsers[1]->id,
                'school' => 'OU Goce Delchev',
                'grade' => '1',
                'interests' => ['drawing', 'games', 'cartoons'],
            ],

            // Parent 3 (Stefan Trajkovski) - 2 children
            [
                'name' => 'Nikola Trajkovski',
                'email' => 'nikola.trajkovski@cyberbuddy.mk',
                'date_of_birth' => '2012-09-18',
                'parent_id' => $parentUsers[2]->id,
                'school' => 'OU Braka Miladinovci',
                'grade' => '7',
                'interests' => ['gaming', 'programming', 'science'],
            ],
            [
                'name' => 'Sara Trajkovska',
                'email' => 'sara.trajkovska@cyberbuddy.mk',
                'date_of_birth' => '2014-12-03',
                'parent_id' => $parentUsers[2]->id,
                'school' => 'OU Braka Miladinovci',
                'grade' => '5',
                'interests' => ['art', 'music', 'reading'],
            ],

            // Parent 4 (Ana Petrovska) - 1 child
            [
                'name' => 'Filip Petrovski',
                'email' => 'filip.petrovski@cyberbuddy.mk',
                'date_of_birth' => '2015-03-25',
                'parent_id' => $parentUsers[3]->id,
                'school' => 'OU Nikola Karev',
                'grade' => '4',
                'interests' => ['gaming', 'sports', 'videos'],
            ],

            // Parent 5 (Vladimir Dimitrov) - 2 children
            [
                'name' => 'Elena Nikolova',
                'email' => 'elena@cyberbuddy.mk',
                'date_of_birth' => '2012-03-10',
                'parent_id' => $parentUsers[4]->id,
                'school' => 'OU Goce Delchev',
                'grade' => '7',
                'interests' => ['social_media', 'music', 'art'],
            ],
            [
                'name' => 'David Dimitrov',
                'email' => 'david.dimitrov@cyberbuddy.mk',
                'date_of_birth' => '2017-06-14',
                'parent_id' => $parentUsers[4]->id,
                'school' => 'OU Goce Delchev',
                'grade' => '2',
                'interests' => ['gaming', 'lego', 'drawing'],
            ],

            // Additional independent children (without parent)
            [
                'name' => 'Sofija Angelovska',
                'email' => 'sofija.angelovska@cyberbuddy.mk',
                'date_of_birth' => '2013-08-22',
                'is_active' => true,
                'school' => 'OU Kiril and Metodij',
                'grade' => '6',
                'interests' => ['sports', 'music', 'videos'],
            ],
            [
                'name' => 'Mihail Stoilov',
                'email' => 'mihail.stoilov@cyberbuddy.mk',
                'date_of_birth' => '2011-11-30',
                'is_active' => true,
                'school' => 'OU Braka Miladinovci',
                'grade' => '8',
                'interests' => ['programming', 'gaming', 'robotics'],
            ],
        ];

        foreach ($children as $childData) {
            $parentId = $childData['parent_id'] ?? null;

            $child = User::create([
                'name' => $childData['name'],
                'email' => $childData['email'],
                'password' => Hash::make('password123'),
                'role' => 'child',
                'date_of_birth' => $childData['date_of_birth'],
                'parent_id' => $parentId,
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
            $child->assignRole('child');
            UserProfile::create([
                'user_id' => $child->id,
                'school' => $childData['school'],
                'grade' => $childData['grade'],
                'language' => 'en',
                'interests' => $childData['interests'],
            ]);
        }
    }
}
