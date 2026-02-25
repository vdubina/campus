<?php

namespace App\Filament\Resources\QuizAttempts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class QuizAttemptForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('quiz_id')
                    ->relationship('quiz', 'title')
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('student_id')
                    ->relationship('student', 'email')
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('enrollment_id')
                    ->relationship('enrollment', 'id')
                    ->searchable()
                    ->preload(),
                TextInput::make('attempt_number')
                    ->required()
                    ->numeric()
                    ->default(1)
                    ->minValue(1),
                DateTimePicker::make('started_at')
                    ->required()
                    ->default(now()),
                DateTimePicker::make('submitted_at'),
                TextInput::make('score')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100),
                Toggle::make('passed')
                    ->default(false),
                Select::make('status')
                    ->required()
                    ->options([
                        'in_progress' => 'In Progress',
                        'submitted' => 'Submitted',
                        'graded' => 'Graded',
                    ])
                    ->default('in_progress'),
                Repeater::make('answers')
                    ->relationship('answers')
                    ->columnSpanFull()
                    ->collapsed()
                    ->schema([
                        Select::make('question_id')
                            ->relationship('question', 'question_text')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Select::make('answer_option_id')
                            ->relationship('answerOption', 'option_text')
                            ->searchable()
                            ->preload(),
                        TextInput::make('answer_text'),
                        Toggle::make('is_correct'),
                        TextInput::make('points_earned')
                            ->required()
                            ->numeric()
                            ->default(0),
                    ]),
            ]);
    }
}
