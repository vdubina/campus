<?php

namespace App\Filament\Resources\Enrollments\Schemas;

use App\Models\Student;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EnrollmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Select::make('student_id')
                    ->relationship('student', 'email')
                    ->getOptionLabelFromRecordUsing(fn (Student $record): string => $record->full_name)
                    ->searchable(['first_name', 'last_name', 'email'])
                    ->required()
                    ->preload(),
                Select::make('course_id')
                    ->relationship('course', 'title')
                    ->required()
                    ->searchable()
                    ->preload(),
                DateTimePicker::make('enrolled_at')
                    ->required()
                    ->default(now()),
                Select::make('status')
                    ->required()
                    ->options([
                        'active' => 'Active',
                        'completed' => 'Completed',
                        'dropped' => 'Dropped',
                    ])
                    ->default('active'),
                TextInput::make('progress_percentage')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->minValue(0)
                    ->maxValue(100)
                    ->suffix('%'),
                DateTimePicker::make('completed_at'),
            ]);
    }
}
