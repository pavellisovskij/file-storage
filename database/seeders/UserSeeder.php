<?php

namespace Database\Seeders;

use App\Helpers\Constants\RolesEnum;
use App\Helpers\Constants\StatusEnum;
use App\Helpers\Constants\StorageSizesEnum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'email' => 'admin@example.com',
            'role' => RolesEnum::ADMIN->value,
            'status' => StatusEnum::ACTIVE->value,
            'storage_size' => StorageSizesEnum::STORAGE_SIZE->value,
            'password' => Hash::make('admin1234'),
        ]);

        Storage::makeDirectory($admin->id . '/thumbnail');

        $user = User::create([
            'email' => 'user@example.com',
            'role' => RolesEnum::USER->value,
            'status' => StatusEnum::ACTIVE->value,
            'storage_size' => StorageSizesEnum::STORAGE_SIZE->value,
            'password' => Hash::make('user1234'),
        ]);

        Storage::makeDirectory($user->id . '/thumbnail');
    }
}
