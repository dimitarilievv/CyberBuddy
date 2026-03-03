<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // === PERMISSIONS ===

        // Модули
        Permission::create(['name' => 'view modules']);
        Permission::create(['name' => 'create modules']);
        Permission::create(['name' => 'edit modules']);
        Permission::create(['name' => 'delete modules']);
        Permission::create(['name' => 'publish modules']);

        // Лекции
        Permission::create(['name' => 'view lessons']);
        Permission::create(['name' => 'create lessons']);
        Permission::create(['name' => 'edit lessons']);
        Permission::create(['name' => 'delete lessons']);

        // Квизови
        Permission::create(['name' => 'take quizzes']);
        Permission::create(['name' => 'create quizzes']);
        Permission::create(['name' => 'edit quizzes']);
        Permission::create(['name' => 'delete quizzes']);
        Permission::create(['name' => 'view quiz results']);

        // Сценарија
        Permission::create(['name' => 'play scenarios']);
        Permission::create(['name' => 'create scenarios']);
        Permission::create(['name' => 'edit scenarios']);
        Permission::create(['name' => 'delete scenarios']);

        // AI
        Permission::create(['name' => 'use ai chat']);
        Permission::create(['name' => 'generate ai content']);
        Permission::create(['name' => 'approve ai content']);

        // Корисници
        Permission::create(['name' => 'manage users']);
        Permission::create(['name' => 'view children progress']);
        Permission::create(['name' => 'view student progress']);

        // Извештаи
        Permission::create(['name' => 'view reports']);
        Permission::create(['name' => 'generate certificates']);
        Permission::create(['name' => 'download certificates']);

        // Администрација
        Permission::create(['name' => 'manage categories']);
        Permission::create(['name' => 'manage tags']);
        Permission::create(['name' => 'manage badges']);
        Permission::create(['name' => 'view activity logs']);
        Permission::create(['name' => 'manage reported content']);

        // === ROLES ===

        // Дете (ученик)
        $child = Role::create(['name' => 'child']);
        $child->givePermissionTo([
            'view modules',
            'view lessons',
            'take quizzes',
            'play scenarios',
            'use ai chat',
            'download certificates',
        ]);

        // Родител
        $parent = Role::create(['name' => 'parent']);
        $parent->givePermissionTo([
            'view modules',
            'view lessons',
            'view children progress',
            'view reports',
            'download certificates',
        ]);

        // Наставник
        $teacher = Role::create(['name' => 'teacher']);
        $teacher->givePermissionTo([
            'view modules',
            'create modules',
            'edit modules',
            'publish modules',
            'view lessons',
            'create lessons',
            'edit lessons',
            'create quizzes',
            'edit quizzes',
            'view quiz results',
            'create scenarios',
            'edit scenarios',
            'use ai chat',
            'generate ai content',
            'view student progress',
            'view reports',
            'generate certificates',
        ]);

        // Админ (сè)
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());
    }
}
