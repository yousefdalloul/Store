<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //using model
        User::create([
            'name'=>'Yousef Dalloul',
            'email' =>'yousef@gmail.com',
            'password' =>Hash::make('password'),
            'phone_number'=>'970592138000',
        ]);
        //using query builder
        DB::table('users')->insert([
            'name'=>'Sys admin',
            'email' =>'sys@gmail.com',
            'password' =>Hash::make('password'),
            'phone_number'=>'970592138111',
        ]);
    }
}
