<?php

namespace App\Filament\Resources\Quizzes\Schemas;

use App\Models\Topic;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class QuizForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('course_id')
                    ->relationship('course', 'title')
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('topic_id')
                    ->relationship('topic', 'title')
                    ->getOptionLabelFromRecordUsing(fn (Topic $record): string => $record->title)
                    ->searchable()
                    ->preload(),
                TextInput::make('title')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('passing_score')
                    ->numeric()
                    ->required()
                    ->default(70)
                    ->minValue(0)
                    ->maxValue(100)
                    ->suffix('%'),
                TextInput::make('time_limit_minutes')
                    ->numeric()
                    ->minValue(1),
                TextInput::make('max_attempts')
                    ->numeric()
                    ->required()
                    ->default(3)
                    ->minValue(1)
                    ->maxValue(20),
                Toggle::make('is_published')
                    ->default(false),
                Repeater::make('questions')
                    ->relationship('questions')
                    ->columnSpanFull()
                    ->reorderableWithButtons()
                    ->collapsed()
                    ->itemLabel(fn (array $state): ?string => $state['question_text'] ?? null)
                    ->schema([
                        Textarea::make('question_text')
                            ->required()
                            ->columnSpanFull(),
                        Select::make('type')
                            ->required()
                            ->options([
                                'single_choice' => 'Single Choice',
                                'multiple_choice' => 'Multiple Choice',
                            ])
                            ->default('single_choice'),
                        TextInput::make('points')
                            ->numeric()
                            ->required()
                            ->default(1)
                            ->minValue(1),
                        TextInput::make('position')
                            ->numeric()
                            ->required()
                            ->default(1)
                            ->minValue(1),
                        Repeater::make('answerOptions')
                            ->relationship('answerOptions')
                            ->columnSpanFull()
                            ->collapsed()
                            ->minItems(2)
                            ->reorderableWithButtons()
                            ->schema([
                                TextInput::make('option_text')
                                    ->required()
                                    ->columnSpan(8),
                                Toggle::make('is_correct')
                                    ->columnSpan(2)
                                    ->default(false),
                                TextInput::make('position')
                                    ->columnSpan(2)
                                    ->numeric()
                                    ->required()
                                    ->default(1)
                                    ->minValue(1),
                            ]),
                    ]),
            ]);
    }
}
