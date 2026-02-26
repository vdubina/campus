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
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
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
