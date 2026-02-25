<?php

namespace App\Filament\Resources\Activities\Schemas;

use App\Models\Contact;
use App\Models\Lead;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ActivityForm
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
                Select::make('lead_id')
                    ->relationship('lead', 'last_name')
                    ->getOptionLabelFromRecordUsing(fn (Lead $record): string => $record->full_name)
                    ->searchable(['first_name', 'last_name', 'email', 'company'])
                    ->preload(),
                Select::make('opportunity_id')
                    ->relationship('opportunity', 'name')
                    ->searchable()
                    ->preload(),
                Select::make('owner_id')
                    ->relationship('owner', 'name')
                    ->searchable()
                    ->preload(),
                TextInput::make('subject')
                    ->required(),
                Select::make('type')
                    ->required()
                    ->default('task')
                    ->options([
                        'task' => 'Task',
                        'call' => 'Call',
                        'email' => 'Email',
                        'meeting' => 'Meeting',
                    ]),
                Select::make('status')
                    ->required()
                    ->default('planned')
                    ->options([
                        'planned' => 'Planned',
                        'in_progress' => 'In Progress',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),
                Select::make('priority')
                    ->required()
                    ->default('normal')
                    ->options([
                        'low' => 'Low',
                        'normal' => 'Normal',
                        'high' => 'High',
                        'urgent' => 'Urgent',
                    ]),
                DateTimePicker::make('due_at'),
                DateTimePicker::make('completed_at'),
                Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }
}
