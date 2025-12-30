<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Report>
 */
class ReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'filter_params' => [
                'date_init' => fake()->dateTimeBetween('1980-01-01', '2010-12-31')->format('Y-m-d'),
                'date_end' => fake()->dateTimeBetween('1980-01-01', '2010-12-31')->format('Y-m-d')
            ],
            'status' => 'pending',
            'user_id' => User::factory()
        ];
    }
}
