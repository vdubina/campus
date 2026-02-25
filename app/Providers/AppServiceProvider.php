<?php

namespace App\Providers;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        TextInput::configureUsing(fn (TextInput $component) => $component->translateLabel());
        Textarea::configureUsing(fn (Textarea $component) => $component->translateLabel());
        Select::configureUsing(fn (Select $component) => $component->translateLabel());
        Toggle::configureUsing(fn (Toggle $component) => $component->translateLabel());
        DatePicker::configureUsing(fn (DatePicker $component) => $component->translateLabel());
        DateTimePicker::configureUsing(fn (DateTimePicker $component) => $component->translateLabel());
        Repeater::configureUsing(fn (Repeater $component) => $component->translateLabel());

        TextColumn::configureUsing(function (TextColumn $component): void {
            $name = $component->getName();
            $base = (string) Str::of($name)->before('.');
            $base = (string) Str::of($base)->replace(['_count', '_sum', '_avg', '_min', '_max'], '');

            $key = Str::snake($base);
            $translated = __($key);
            $label = $translated !== $key ? $translated : Str::headline($base);

            $component->label($label);
        });

        IconColumn::configureUsing(function (IconColumn $component): void {
            $name = $component->getName();
            $base = (string) Str::of($name)->before('.');
            $base = (string) Str::of($base)->replace(['_count', '_sum', '_avg', '_min', '_max'], '');

            $key = Str::snake($base);
            $translated = __($key);
            $label = $translated !== $key ? $translated : Str::headline($base);

            $component->label($label);
        });
    }
}
