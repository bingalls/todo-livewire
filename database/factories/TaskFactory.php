<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    public function definition(): array
    {
        return [
            'position' => $this->faker->unique()->randomNumber(2),
            'taskname' => $this->faker->unique()->word(),
            'project' => $this->faker->randomElement([
                'bug',
                'feature',
                'onboarding',
                'sprint1',
                'upgrade',
            ])
        ];
    }
}
