<?php

namespace App\Filament\Resources\Leads\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class LeadForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('account_id')
                    ->relationship('account', 'name')
                    ->searchable()
                    ->preload(),
                Select::make('owner_id')
                    ->relationship('owner', 'name')
                    ->searchable()
                    ->preload(),
                TextInput::make('first_name')
                    ->required(),
                TextInput::make('last_name')
                    ->required(),
                TextInput::make('company'),
                TextInput::make('email')
                    ->email(),
                TextInput::make('phone')
                    ->tel(),
                TextInput::make('source'),
                Select::make('status')
                    ->required()
                    ->default('new')
                    ->options([
                        'new' => 'New',
                        'contacted' => 'Contacted',
                        'qualified' => 'Qualified',
                        'proposal' => 'Proposal',
                        'unqualified' => 'Unqualified',
                        'converted' => 'Converted',
                    ]),
                Select::make('rating')
                    ->options([
                        'cold' => 'Cold',
                        'warm' => 'Warm',
                        'hot' => 'Hot',
                    ]),
                TextInput::make('estimated_value')
                    ->numeric()
                    ->prefix('$'),
                DateTimePicker::make('next_follow_up_at'),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
