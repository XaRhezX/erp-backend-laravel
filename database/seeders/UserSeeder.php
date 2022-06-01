<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Root - Super User',
            'email' => 'root@super.user',
            'password' => Hash::make('root@super.user'),
        ]);

        $user->createToken('API Token')->plainTextToken;
    }
}
