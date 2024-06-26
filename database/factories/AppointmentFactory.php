<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $index = fake()->numberBetween(0, 1);

        $remark = [
            // 'Cancelled due to incorrect mobile number',
            // 'Your appointment has been approved.',
        ];

        $status = [
            // 'Cancelled',
            // 'Approved',
        ];
        return [
            'AppointmentNumber' => fake()->biasedNumberBetween(10000, 99999),
            'AppointmentDate' => fake()->date(),
            'AppointmentTime_id' => fake()->numberBetween(1, 14),
            'name' =>  fake()->name(),
            'patient_id' => fake()->numberBetween(1, 5),
            'doctor_id' => fake()->numberBetween(1, 13),
            'Message' => fake()->text(50),
            'ApplyDate' => fake()->dateTime('now'),
            // 'Remark' => $remark[$index],
            // 'Status' => $status[$index],
        ];
    }
}