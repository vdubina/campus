<?php

namespace App\Providers;

use App\Models\Certification;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Instructor;
use App\Models\Lead;
use App\Models\Opportunity;
use App\Models\Account;
use App\Models\Activity;
use App\Models\Contact;
use App\Models\Permission;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Role;
use App\Models\Student;
use App\Models\Topic;
use App\Models\User;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Support\HtmlString;

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
        Schema::defaultStringLength(191);

        FilamentView::registerRenderHook(
            PanelsRenderHook::HEAD_END,
            static fn (): HtmlString => new HtmlString(<<<'HTML'
                <style>
                    .fc .fc-dayGridMonth-view .fc-daygrid-day-frame {
                        aspect-ratio: 1 / 1;
                        min-height: 8.5rem;
                    }

                    .fi-sidebar-group-btn,
                    .fi-sidebar-group-label,
                    .fi-sidebar-group-collapse-btn,
                    .fi-sidebar-group-dropdown-trigger-btn {
                        color: rgb(239 60 61) !important;
                    }

                    .fi-sidebar-group-btn:hover,
                    .fi-sidebar-group-btn:focus-visible {
                        color: rgb(255 94 95) !important;
                    }

                    .fi-sidebar-item.fi-active > .fi-sidebar-item-btn,
                    .fi-sidebar-item.fi-active > .fi-sidebar-item-btn .fi-sidebar-item-label,
                    .fi-sidebar-item.fi-active > .fi-sidebar-item-btn .fi-sidebar-item-icon {
                        color: #111 !important;
                    }

                    .fi-sidebar-item.fi-active > .fi-sidebar-item-btn {
                        background-color: rgba(17, 17, 17, 0.16) !important;
                    }

                    .dark .fi-sidebar-item.fi-active > .fi-sidebar-item-btn,
                    .dark .fi-sidebar-item.fi-active > .fi-sidebar-item-btn .fi-sidebar-item-label,
                    .dark .fi-sidebar-item.fi-active > .fi-sidebar-item-btn .fi-sidebar-item-icon {
                        color: #fff !important;
                    }

                    .fi-btn.fi-color.fi-color-primary,
                    .fi-btn.fi-color-primary {
                        background-color: #111 !important;
                        color: #fff !important;
                    }

                    .fi-btn.fi-color.fi-color-primary > .fi-icon,
                    .fi-btn.fi-color-primary > .fi-icon {
                        color: #fff !important;
                    }

                    .fi-btn.fi-color.fi-color-primary.fi-force-enabled,
                    .fi-btn.fi-color-primary.fi-force-enabled,
                    .fi-btn.fi-color.fi-color-primary:not(.fi-disabled):not([disabled]):hover,
                    .fi-btn.fi-color-primary:not(.fi-disabled):not([disabled]):hover {
                        background-color: #000 !important;
                        color: #fff !important;
                    }

                    .fi-btn.fi-color.fi-color-primary:focus-visible,
                    .fi-btn.fi-color-primary:focus-visible {
                        box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.16), 0 0 0 4px rgba(17, 17, 17, 0.5) !important;
                    }

                    .fi-btn.fi-color.fi-color-danger,
                    .fi-btn.fi-color-danger {
                        background-color: rgb(239 60 61) !important;
                        color: #fff !important;
                    }

                    .fi-btn.fi-color.fi-color-danger > .fi-icon,
                    .fi-btn.fi-color-danger > .fi-icon {
                        color: #fff !important;
                    }

                    .fi-btn.fi-color.fi-color-danger.fi-force-enabled,
                    .fi-btn.fi-color-danger.fi-force-enabled,
                    .fi-btn.fi-color.fi-color-danger:not(.fi-disabled):not([disabled]):hover,
                    .fi-btn.fi-color-danger:not(.fi-disabled):not([disabled]):hover {
                        background-color: rgb(255 94 95) !important;
                        color: #fff !important;
                    }
                </style>
            HTML)
        );

        Gate::before(function (User $user, string $ability, mixed $arguments = null): ?bool {
            $actionByAbility = [
                'viewAny' => 'view',
                'view' => 'view',
                'create' => 'create',
                'update' => 'update',
                'delete' => 'delete',
                'deleteAny' => 'delete',
                'forceDelete' => 'delete',
                'forceDeleteAny' => 'delete',
                'restore' => 'delete',
                'restoreAny' => 'delete',
            ];

            $permissionPrefixByModel = [
                Account::class => 'account',
                Contact::class => 'contact',
                Lead::class => 'lead',
                Opportunity::class => 'opportunity',
                Activity::class => 'activity',
                User::class => 'user',
                Role::class => 'role',
                Permission::class => 'permission',
                Instructor::class => 'instructor',
                Student::class => 'student',
                Course::class => 'course',
                Topic::class => 'topic',
                Quiz::class => 'quiz',
                QuizAttempt::class => 'quiz_attempt',
                Enrollment::class => 'enrollment',
                Certification::class => 'certification',
            ];

            $target = is_array($arguments) ? ($arguments[0] ?? null) : $arguments;

            if ($target instanceof Model) {
                $target = $target::class;
            }

            if (! is_string($target)) {
                return null;
            }

            $action = $actionByAbility[$ability] ?? null;
            $prefix = $permissionPrefixByModel[$target] ?? null;

            if (! $action || ! $prefix) {
                return null;
            }

            $requiredPermission = "{$prefix}.{$action}";

            return $user->getAllPermissions()->contains('name', $requiredPermission);
        });

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
