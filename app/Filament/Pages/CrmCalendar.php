<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\LeadsCalendarWidget;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\Widget;
use Filament\Widgets\WidgetConfiguration;

class CrmCalendar extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static ?int $navigationSort = 35;

    public static function getNavigationLabel(): string
    {
        return __('admin.crm_calendar.navigation');
    }

    public function getTitle(): string
    {
        return __('admin.crm_calendar.title');
    }

    public static function getNavigationGroup(): string|\UnitEnum|null
    {
        return __('resources.groups.sales_crm');
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(1)
                    ->schema(fn (): array => $this->getWidgetsSchemaComponents($this->getWidgets())),
            ]);
    }

    /**
     * @return array<class-string<Widget> | WidgetConfiguration>
     */
    protected function getWidgets(): array
    {
        return [
            LeadsCalendarWidget::class,
        ];
    }
}
