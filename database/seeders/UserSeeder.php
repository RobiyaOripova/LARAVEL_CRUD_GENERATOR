<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $username = config('system.ADMIN_USERNAME');
        $password = bcrypt(config('system.ADMIN_PASSWORD'));

        if (empty($username) || empty($password)) {
            dd('Username or password is empty in .env');
        }
        $user = User::create([
            'username' => $username,
            'first_name' => $username,
            'last_name' => $username,
            'phone' => '998111234567',
            'password' => $password,
        ]);

        $user->role()->create([
            'user_id' => $user->id,
            'role' => 'admin',
        ]);
    }
}
