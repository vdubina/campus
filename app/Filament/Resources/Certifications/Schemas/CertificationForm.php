<?php

namespace App\Filament\Resources\Certifications\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CertificationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Select::make('enrollment_id')
                    ->relationship('enrollment', 'id')
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('student_id')
                    ->relationship('student', 'email')
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('course_id')
                    ->relationship('course', 'title')
                    ->required()
                    ->searchable()
                    ->preload(),
                TextInput::make('certificate_number')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                DateTimePicker::make('issued_at')
                    ->required(),
                DateTimePicker::make('valid_until'),
                Select::make('status')
                    ->required()
                    ->options([
                        'issued' => 'Issued',
                        'revoked' => 'Revoked',
                        'expired' => 'Expired',
                    ])
                    ->default('issued'),
                TextInput::make('file_path')
                    ->columnSpanFull(),
            ]);
    }
}
