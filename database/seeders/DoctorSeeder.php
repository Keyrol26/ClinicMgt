<?php

namespace Database\Seeders;

use App\Models\Patient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;
use App\Models\Doctor;
use App\Models\User;
use Faker\Factory as Faker;
class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $users = [
        //     [
        //         'name' => fake()->name,
        //         'email' => fake()->unique()->safeEmail(),
        //         'role' => 1,
        //         'password' => bcrypt('123456'),
        //     ],
        //     [
        //         'name' => fake()->name,
        //         'email' => fake()->unique()->safeEmail(),
        //         'role' => 1,
        //         'password' => bcrypt('123456'),
        //     ],
        //     [
        //         'name' => fake()->name,
        //         'email' => fake()->unique()->safeEmail(),
        //         'role' => 1,
        //         'password' => bcrypt('123456'),
        //     ],

        // ];
        // foreach ($users as $userData) {
        //     $user = User::create($userData);

        //     // Create associated role-specific record
        //     if ($user->role == 'user') {
        //         Patient::create(['user_id' => $user->id]);
        //     } elseif ($user->role == 'doctor') {
        //         Doctor::create(['user_id' => $user->id, 'specialization_id' => rand(1,13)]);
        //     } elseif ($user->role == 'admin') {
        //         Admin::create(['user_id' => $user->id]);
        //     }
        // };

        $faker = Faker::create();

        // Create 10 doctor 
        for ($i = 0; $i < 15; $i++) {
            $user = User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'role' => 1, // Set role to 1 for doctors
                'password' => bcrypt('123456'),
                'email_verified_at' => now(),
            ]);
            // Create associated doctor record
            if ($user->role == 'user') {
                Patient::create(['user_id' => $user->id]);
            } elseif ($user->role == 'doctor') {
                Doctor::create(['user_id' => $user->id, 'specialization_id' => rand(1,13)]);
            } elseif ($user->role == 'admin') {
                Admin::create(['user_id' => $user->id]);
            }
        }

        //user
        for ($i = 0; $i < 15; $i++) {
            $user = User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'role' => 0, // Set role to 1 for doctors
                'password' => bcrypt('123456'),
                'email_verified_at' => now(),
            ]);
            // Create associated doctor record
            if ($user->role == 'user') {
                Patient::create(['user_id' => $user->id]);
            } elseif ($user->role == 'doctor') {
                Doctor::create(['user_id' => $user->id, 'specialization_id' => rand(1,13)]);
            } elseif ($user->role == 'admin') {
                Admin::create(['user_id' => $user->id]);
            }
        }
    }
}
