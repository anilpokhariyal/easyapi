<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'username' => Str::random(10),
                'email' => Str::random(10).'@gmail.com',
                'full_name' => Str::random(10),
                'phone' => Str::random(10, 10),
                'city' => Str::random(8),
            ],
            [
                'username' => Str::random(10),
                'email' => Str::random(10).'@gmail.com',
                'full_name' => Str::random(10),
                'phone' => Str::random(10, 10),
                'city' => Str::random(8),
            ],
            [
                'username' => Str::random(10),
                'email' => Str::random(10).'@gmail.com',
                'full_name' => Str::random(10),
                'phone' => Str::random(10, 10),
                'city' => Str::random(8),
            ],
            [
                'username' => Str::random(10),
                'email' => Str::random(10).'@gmail.com',
                'full_name' => Str::random(10),
                'phone' => Str::random(10, 10),
                'city' => Str::random(8),
            ]]);
    }
}
