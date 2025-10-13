<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Schedule>
 */
class ScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'movie_id'    => $this->faker->numberBetween(1, 10000),
            'cinema_id'   => $this->faker->numberBetween(1, 5),
            'city_id'     => $this->faker->numberBetween(1, 500),
            'timeslot_id' => $this->faker->numberBetween(1, 10),
        ];
    }
}
