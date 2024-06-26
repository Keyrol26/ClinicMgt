<?php

namespace Database\Seeders;

use App\Models\BookingTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BookingTime::create([
            'AppointmentTime' => '8.00 AM',
            'created_at' => now(),
        ]);
        BookingTime::create([
            'AppointmentTime' => '9.00 AM',
            'created_at' => now(),
        ]);
        BookingTime::create([
            'AppointmentTime' => '10.00 AM',
            'created_at' => now(),
        ]);
        BookingTime::create([
            'AppointmentTime' => '11.00 AM',
            'created_at' => now(),
        ]);
        BookingTime::create([
            'AppointmentTime' => '12.00 PM',
            'created_at' => now(),
        ]);
        BookingTime::create([
            'AppointmentTime' => '2.00 PM',
            'created_at' => now(),
        ]);
        BookingTime::create([
            'AppointmentTime' => '3.00 PM',
            'created_at' => now(),
        ]);
        BookingTime::create([
            'AppointmentTime' => '4.00 PM',
            'created_at' => now(),
        ]);
        BookingTime::create([
            'AppointmentTime' => '5.00 PM',
            'created_at' => now(),
        ]);
        BookingTime::create([
            'AppointmentTime' => '6.00 PM',
            'created_at' => now(),
        ]);
        BookingTime::create([
            'AppointmentTime' => '7.00 PM',
            'created_at' => now(),
        ]);
        BookingTime::create([
            'AppointmentTime' => '8.00 PM',
            'created_at' => now(),
        ]);
        BookingTime::create([
            'AppointmentTime' => '9.00 PM',
            'created_at' => now(),
        ]);
        BookingTime::create([
            'AppointmentTime' => '10.00 PM',
            'created_at' => now(),
        ]);
    }
}
