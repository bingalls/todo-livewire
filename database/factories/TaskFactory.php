<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;

class TaskFactory extends Factory
{
    #[ArrayShape(['position' => 'int', 'taskname' => 'string', 'project' => 'string'])]
    public function definition(): array
    {
        return [
            'position' => $this->faker->unique()->randomNumber(2),
            'taskname' => $this->faker->unique()->words(3, true),
            'project' => $this->faker->randomElement([
                'feature',
                'issue',
                'onboarding',
                'sprint1',
                'upgrade',
            ]),
        ];
    }
}
