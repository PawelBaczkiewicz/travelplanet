<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Shared\Domain\ValueObjects\RoleEnum;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@travelplanet',
            'role' => RoleEnum::ADMIN,
            'password' => Hash::make('travelplanet'),
        ]);

        User::factory()->create([
            'name' => 'Restricted User',
            'email' => 'restricted@travelplanet',
            'role' => RoleEnum::RESTRICTED,
            'password' => Hash::make('travelplanet'),
        ]);

        User::factory()->create([
            'name' => 'Editor User',
            'email' => 'editor@travelplanet',
            'role' => RoleEnum::EDITOR,
            'password' => Hash::make('travelplanet'),
        ]);
    }
}
