<?php
namespace Authorizo\Database\Seeders;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Workbench\Database\Factories\UserFactory;

class AuthorizoSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $user = DB::table('users')
            ->where('email', 'admin@example.com')
            ->exists();

        if (! $user) {
            $userModel = config('auth.providers.users.model');

            $userModel::create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('12345678'),
                'role_id' => 1,
            ]);
        }
    }
}
