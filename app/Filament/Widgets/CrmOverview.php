<?php

namespace App\Filament\Widgets;

use App\Models\Lead;
use App\Models\Opportunity;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CrmOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make(__('admin.crm_overview.open_leads'), (string) Lead::query()->whereNotIn('status', ['converted', 'unqualified'])->count())
                ->description(__('admin.crm_overview.open_leads_desc'))
                ->color('warning'),
            Stat::make(__('admin.crm_overview.open_opportunities'), (string) Opportunity::query()->where('status', 'open')->count())
                ->description(__('admin.crm_overview.open_opportunities_desc'))
                ->color('info'),
            Stat::make(
                __('admin.crm_overview.pipeline_value'),
                '$' . number_format((float) Opportunity::query()->where('status', 'open')->sum('amount'), 2)
            )
                ->description(__('admin.crm_overview.pipeline_value_desc'))
                ->color('success'),
        ];
    }
}
