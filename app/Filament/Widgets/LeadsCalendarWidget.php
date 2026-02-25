<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Leads\LeadResource;
use App\Models\Lead;
use Guava\Calendar\Enums\CalendarViewType;
use Guava\Calendar\Filament\CalendarWidget;
use Guava\Calendar\ValueObjects\CalendarEvent;
use Guava\Calendar\ValueObjects\FetchInfo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class LeadsCalendarWidget extends CalendarWidget
{
    protected CalendarViewType $calendarView = CalendarViewType::DayGridMonth;

    protected bool $eventClickEnabled = true;

    protected array $options = [
        'allDaySlot' => false,
        'expandRows' => true,
        'headerToolbar' => [
            'start' => 'today prev,next',
            'center' => 'title',
            'end' => 'dayGridMonth,timeGridWeek',
        ],
        'height' => 'auto',
        'nowIndicator' => true,
        'slotDuration' => '01:00:00',
        'slotMinTime' => '00:00:00',
        'slotMaxTime' => '24:00:00',
        'stickyHeaderDates' => true,
    ];

    protected function getEvents(FetchInfo $info): Collection | array | Builder
    {
        return Lead::query()
            ->whereNotNull('next_follow_up_at')
            ->whereBetween('next_follow_up_at', [$info->start, $info->end])
            ->with(['account:id,name', 'owner:id,name'])
            ->orderBy('next_follow_up_at')
            ->get()
            ->map(function (Lead $lead): CalendarEvent {
                $startsAt = $lead->next_follow_up_at;
                $statusColor = match ($lead->status) {
                    'new' => '#2563eb',
                    'contacted' => '#0891b2',
                    'qualified' => '#7c3aed',
                    'proposal' => '#d97706',
                    'converted' => '#16a34a',
                    'unqualified' => '#dc2626',
                    default => '#6b7280',
                };

                return CalendarEvent::make($lead)
                    ->title($lead->full_name)
                    ->start($startsAt)
                    ->end($startsAt->copy()->addHour())
                    ->backgroundColor($statusColor)
                    ->textColor('#ffffff')
                    ->extendedProps([
                        'account' => $lead->account?->name,
                        'owner' => $lead->owner?->name,
                        'status' => $lead->status,
                    ])
                    ->url(LeadResource::getUrl('edit', ['record' => $lead]), '_self');
            });
    }
}
