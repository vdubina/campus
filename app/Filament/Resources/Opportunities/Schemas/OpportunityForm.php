<?php

namespace App\Filament\Resources\Opportunities\Schemas;

use App\Models\Contact;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class OpportunityForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('account_id')
                    ->relationship('account', 'name')
                    ->searchable()
                    ->preload(),
                Select::make('contact_id')
                    ->relationship('contact', 'last_name')
                    ->getOptionLabelFromRecordUsing(fn (Contact $record): string => $record->full_name)
                    ->searchable(['first_name', 'last_name', 'email'])
                    ->preload(),
                Select::make('owner_id')
                    ->relationship('owner', 'name')
                    ->searchable()
                    ->preload(),
                TextInput::make('name')
                    ->required(),
                Select::make('stage')
                    ->required()
                    ->default('qualification')
                    ->options([
                        'qualification' => 'Qualification',
                        'needs_analysis' => 'Needs Analysis',
                        'proposal' => 'Proposal',
                        'negotiation' => 'Negotiation',
                        'closed_won' => 'Closed Won',
                        'closed_lost' => 'Closed Lost',
                    ]),
                Select::make('status')
                    ->required()
                    ->default('open')
                    ->options([
                        'open' => 'Open',
                        'won' => 'Won',
                        'lost' => 'Lost',
                    ]),
                TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->prefix('$')
                    ->default(0),
                TextInput::make('probability')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->suffix('%')
                    ->default(10),
                DatePicker::make('close_date'),
                Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }
}
