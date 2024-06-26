<?php

namespace Database\Seeders;

use App\Models\Specialization;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreateSpecializationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Specialization::create([
            'specialization' => 'Orthopedics',
            'created_at' => now(),
        ]);
        Specialization::create([
            'specialization' => 'Internal Medicine',
            'created_at' => now(),
        ]);
        Specialization::create([
            'specialization' => 'Obstetrics and Gynecology',
            'created_at' => now(),
        ]);
        Specialization::create([
            'specialization' => 'Dermatology',
            'created_at' => now(),
        ]);
        Specialization::create([
            'specialization' => 'Pediatrics',
            'created_at' => now(),
        ]);
        Specialization::create([
            'specialization' => 'Radiology',
            'created_at' => now(),
        ]);
        Specialization::create([
            'specialization' => 'General Surgery',
            'created_at' => now(),
        ]);
        Specialization::create([
            'specialization' => 'Ophthalmology',
            'created_at' => now(),
        ]);
        Specialization::create([
            'specialization' => 'Family Medicine',
            'created_at' => now(),
        ]);
        Specialization::create([
            'specialization' => 'Chest Medicine',
            'created_at' => now(),
        ]);
        Specialization::create([
            'specialization' => 'Anesthesia',
            'created_at' => now(),
        ]);
        Specialization::create([
            'specialization' => 'Pathology',
            'created_at' => now(),
        ]);
        Specialization::create([
            'specialization' => 'ENT',
            'created_at' => now(),
        ]);
    }
}
