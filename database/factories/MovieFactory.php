<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Movie>
 */
class MovieFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startHour = $this->faker->numberBetween(10, 20); // start between 10 AM and 8 PM
        $startMinute = $this->faker->randomElement([0, 15, 30, 45]);
        $startTimestamp = strtotime(sprintf('%02d:%02d', $startHour, $startMinute));
        $start = date('H:i', $startTimestamp);

        // Add 1 to 3 hours to start time
        $durationHours = $this->faker->numberBetween(1, 3);
        $endTimestamp = strtotime($start) + ($durationHours * 60 * 60);
        $end = date('H:i', $endTimestamp);

        return [
            'title' => $this->faker->sentence(3),
            'author' => $this->faker->name(),
            'director' => $this->faker->name(),
            'cinema' => 'Cinema ' . $this->faker->numberBetween(1, 4),
            'start' => $start,
            'end' => $end,
        ];
        
    }
}
