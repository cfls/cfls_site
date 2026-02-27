<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Question;

class ValidateQuestions extends Command
{
    protected $signature = 'questions:fix';
    protected $description = 'Ensure only 3 options including correct answer';

    public function handle()
    {
        $this->info("Fixing questions...");

        $questions = Question::where('syllabu_id', 3)
            ->where('type', 'video-choice')
            ->where('status', 1)
            ->get();

        foreach ($questions as $question) {

            $options = $question->options; // ya es array

            if (!is_array($options)) {
                continue;
            }

            if (count($options) <= 3) {
                continue; // ya está bien
            }

            $correctAnswer = $question->answer;

            // Separar correcta e incorrectas
            $correctOption = collect($options)
                ->firstWhere('value', $correctAnswer);

            $wrongOptions = collect($options)
                ->where('value', '!=', $correctAnswer)
                ->shuffle()
                ->take(2)
                ->values()
                ->toArray();

            if (!$correctOption) {
                $this->error("ID {$question->id} - Correct answer not found");
                continue;
            }

            $newOptions = collect($wrongOptions)
                ->push($correctOption)
                ->shuffle()
                ->values()
                ->toArray();

            $question->update([
                'options' => $newOptions
            ]);

            $this->info("ID {$question->id} fixed.");
        }

        $this->info("Process completed ✅");
    }
}