<?php

namespace App\Filament\Widgets;

use App\Models\Certification;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Quiz;
use App\Models\Student;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LmsOverview extends StatsOverviewWidget
{
    public static function canView(): bool
    {
        /** @var User|null $user */
        $user = auth()->user();

        return (bool) $user?->getAllPermissions()->contains('name', 'lms.dashboard.view');
    }

    protected function getStats(): array
    {
        return [
            Stat::make(__('admin.lms_overview.active_courses'), (string) Course::query()->where('status', 'published')->count())
                ->description(__('admin.lms_overview.active_courses_desc'))
                ->color('primary'),
            Stat::make(__('admin.lms_overview.students'), (string) Student::query()->where('is_active', true)->count())
                ->description(__('admin.lms_overview.students_desc'))
                ->color('info'),
            Stat::make(__('admin.lms_overview.active_enrollments'), (string) Enrollment::query()->where('status', 'active')->count())
                ->description(__('admin.lms_overview.active_enrollments_desc'))
                ->color('warning'),
            Stat::make(__('admin.lms_overview.published_quizzes'), (string) Quiz::query()->where('is_published', true)->count())
                ->description(__('admin.lms_overview.published_quizzes_desc'))
                ->color('gray'),
            Stat::make(__('admin.lms_overview.certificates'), (string) Certification::query()->count())
                ->description(__('admin.lms_overview.certificates_desc'))
                ->color('success'),
        ];
    }
}
