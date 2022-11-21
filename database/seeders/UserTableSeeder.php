<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use database\factories\UserFactory;


class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->times(10)->create();
    }
}
