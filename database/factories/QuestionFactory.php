<?php

namespace Database\Factories;

use App\Enums\QuestionDifficultyLevelEnum;
use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Question::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $correct_answer = fake()->randomElement(['answer_1', 'answer_2', 'answer_3', 'answer_4', 'answer_5']);
        return [
            'question' => str_replace('.', '?', fake()->sentence(15)),
            'difficulty_level' => fake()->randomElement([QuestionDifficultyLevelEnum::BEGINNER, QuestionDifficultyLevelEnum::INTERMEDIATE, QuestionDifficultyLevelEnum::ADVANCED]),
            'anwsers' => [
                'answer_1' => [
                    'is_correct' => $correct_answer === 'answer_1',
                    'description' => fake()->sentence(10),
                ],
                'answer_2' => [
                    'is_correct' => $correct_answer === 'answer_2',
                    'description' => fake()->sentence(10),
                ],
                'answer_3' => [
                    'is_correct' => $correct_answer === 'answer_3',
                    'description' => fake()->sentence(10),
                ],
                'answer_4' => [
                    'is_correct' => $correct_answer === 'answer_4',
                    'description' => fake()->sentence(10),
                ],
                'answer_5' => [
                    'is_correct' => $correct_answer === 'answer_5',
                    'description' => fake()->sentence(10),
                ],
            ],
        ];
    }
}
