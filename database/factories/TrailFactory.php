<?php

namespace Database\Factories;

use App\Enums\TrailDifficultyLevelEnum;
use App\Models\Trail;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrailFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Trail::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'description' => fake()->sentence(3),
            'difficulty_level' => fake()->randomElement([TrailDifficultyLevelEnum::MANUAL, TrailDifficultyLevelEnum::BEGINNER, TrailDifficultyLevelEnum::INTERMEDIATE, TrailDifficultyLevelEnum::ADVANCED]),
        ];
    }
}
