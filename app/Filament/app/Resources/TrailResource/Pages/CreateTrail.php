<?php

namespace App\Filament\app\Resources\TrailResource\Pages;

use App\Enums\TrailDifficultyLevelEnum;
use App\Filament\app\Resources\TrailResource;
use App\Models\Question;
use App\Models\Trail;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;

class CreateTrail extends CreateRecord
{
    protected static string $resource = TrailResource::class;

    protected function handleRecordCreation(array $data): Trail
    {
        $selectedQuestions = [];

        if (!(TrailDifficultyLevelEnum::tryFrom($data['difficulty_level']) == TrailDifficultyLevelEnum::MANUAL)) {
            $questions = Question::where('difficulty_level', TrailDifficultyLevelEnum::tryFrom($data['difficulty_level']))->get();

            if (count($questions) < 25) {
                Notification::make()
                    ->warning()
                    ->title('Não há questões suficientes!')
                    ->body('Necessita de pelo menos 25 questões do nível de dificuldade selecionado para criar uma trilha.')
                    ->send();
                $this->halt();
            }

            $selectedQuestions = fake()->randomElements($questions, 25);
        }

        try {
            DB::beginTransaction();

            $newTrail = Trail::create([
                'description' => $data['description'],
                'difficulty_level' => $data['difficulty_level'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($selectedQuestions as $key => $question) {
                $newTrail->questions()->attach([
                    $question->id => ['level' => ceil(($key + 1) / 5)],
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $newTrail;
    }
}
