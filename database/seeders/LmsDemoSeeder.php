<?php

namespace Database\Seeders;

use App\Models\AnswerOption;
use App\Models\Certification;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Instructor;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Role;
use App\Models\Student;
use App\Models\Topic;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LmsDemoSeeder extends Seeder
{
    public function run(): void
    {
        mt_srand(2026);

        $instructorRole = Role::query()->firstOrCreate(['name' => 'Instructor', 'guard_name' => 'web']);
        $studentRole = Role::query()->firstOrCreate(['name' => 'Student', 'guard_name' => 'web']);

        $instructors = [
            ['Ігор', 'Кравченко', 'Кібербезпека'],
            ['Олена', 'Мельник', 'БПЛА мультиротор'],
            ['Сергій', 'Ткаченко', 'FPV та крило'],
            ['Наталія', 'Бондар', 'Топографія та зв\'язок'],
            ['Андрій', 'Гриценко', 'OSINT та супутникові мережі'],
        ];

        $instructorProfiles = collect();

        foreach ($instructors as $index => [$firstName, $lastName, $expertise]) {
            $email = 'instructor' . ($index + 1) . '@campus-crm.local';

            $user = User::query()->updateOrCreate([
                'email' => $email,
            ], [
                'name' => "{$firstName} {$lastName}",
                'password' => Hash::make('password'),
            ]);

            $user->syncRoles([$instructorRole]);

            $profile = Instructor::query()->updateOrCreate([
                'email' => $email,
            ], [
                'user_id' => $user->id,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'phone' => '+38067' . str_pad((string) (100000 + $index), 6, '0', STR_PAD_LEFT),
                'expertise' => $expertise,
                'bio' => "Викладач напряму: {$expertise}.",
                'is_active' => true,
            ]);

            $instructorProfiles->push($profile);
        }

        $firstNames = ['Олександр', 'Марія', 'Владислав', 'Катерина', 'Дмитро', 'Ірина', 'Максим', 'Юлія', 'Роман', 'Анна'];
        $lastNames = ['Шевченко', 'Коваль', 'Савченко', 'Петренко', 'Поліщук', 'Романюк', 'Лисенко', 'Данилюк', 'Кучер', 'Остапенко'];

        $studentProfiles = collect();

        for ($i = 1; $i <= 30; $i++) {
            $firstName = $firstNames[($i - 1) % count($firstNames)];
            $lastName = $lastNames[(($i - 1) * 3) % count($lastNames)];
            $email = 'student' . str_pad((string) $i, 2, '0', STR_PAD_LEFT) . '@campus-crm.local';

            $user = User::query()->updateOrCreate([
                'email' => $email,
            ], [
                'name' => "{$firstName} {$lastName}",
                'password' => Hash::make('password'),
            ]);

            $user->syncRoles([$studentRole]);

            $student = Student::query()->updateOrCreate([
                'email' => $email,
            ], [
                'user_id' => $user->id,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'phone' => '+38099' . str_pad((string) (100000 + $i), 6, '0', STR_PAD_LEFT),
                'date_of_birth' => Carbon::create(1994 + ($i % 8), ($i % 12) + 1, (($i * 2) % 27) + 1)->toDateString(),
                'external_id' => 'STD-' . str_pad((string) $i, 4, '0', STR_PAD_LEFT),
                'is_active' => true,
            ]);

            $studentProfiles->push($student);
        }

        $courseTitles = [
            'КІБЕРБЕЗПЕКА ТА ЦИФРОВА ГРАМОТНІСТЬ',
            'КЕРУВАННЯ БПЛА МУЛЬТИРОТОР',
            'КЕРУВАННЯ БПЛА КРИЛО',
            'КЕРУВАННЯ FPV ДРОНАМИ',
            'ОСНОВИ ТОПОГРАФІЇ',
            'ОСНОВИ ПЕРСОНАЛЬНОГО РАДІОЗВ\'ЯЗКУ',
            'ВИКОРИСТАННЯ СУПУТНИКОВОГО ІНТЕРНЕТУ',
            'МЕТОДИ ПОКРАЩЕННЯ ЗВ\'ЯЗКУ',
            'КІБЕРБЕЗПЕКА ТА OSINT',
        ];

        $courses = collect();

        foreach ($courseTitles as $index => $title) {
            $course = Course::query()->updateOrCreate([
                'code' => 'LMS-' . str_pad((string) ($index + 1), 3, '0', STR_PAD_LEFT),
            ], [
                'instructor_id' => $instructorProfiles[$index % $instructorProfiles->count()]->id,
                'title' => $title,
                'slug' => Str::slug($title),
                'description' => "Навчальний курс: {$title}",
                'level' => ['beginner', 'intermediate', 'advanced'][$index % 3],
                'status' => 'published',
                'duration_hours' => 12 + ($index * 2),
                'published_at' => Carbon::now()->subDays(60 - $index),
            ]);

            $courses->push($course);

            $topicTitles = ['Вступ', 'Теоретичний блок', 'Практичний блок', 'Підсумкове заняття'];

            foreach ($topicTitles as $position => $topicTitle) {
                Topic::query()->updateOrCreate([
                    'course_id' => $course->id,
                    'position' => $position + 1,
                ], [
                    'title' => $topicTitle,
                    'description' => "{$topicTitle} для курсу {$title}",
                    'is_published' => true,
                ]);
            }

            $quiz = Quiz::query()->updateOrCreate([
                'course_id' => $course->id,
                'title' => 'Підсумковий тест',
            ], [
                'topic_id' => $course->topics()->orderBy('position')->value('id'),
                'description' => "Підсумкова перевірка знань за курсом {$title}",
                'passing_score' => 70,
                'time_limit_minutes' => 30,
                'max_attempts' => 3,
                'is_published' => true,
            ]);

            for ($q = 1; $q <= 5; $q++) {
                $question = Question::query()->updateOrCreate([
                    'quiz_id' => $quiz->id,
                    'position' => $q,
                ], [
                    'question_text' => "Питання {$q} до курсу {$title}",
                    'type' => 'single_choice',
                    'points' => 1,
                ]);

                for ($o = 1; $o <= 4; $o++) {
                    AnswerOption::query()->updateOrCreate([
                        'question_id' => $question->id,
                        'position' => $o,
                    ], [
                        'option_text' => "Варіант {$o} для питання {$q}",
                        'is_correct' => $o === 1,
                    ]);
                }
            }
        }

        foreach ($studentProfiles as $studentIndex => $student) {
            $assignedCourseIndexes = [
                $studentIndex % $courses->count(),
                ($studentIndex + 3) % $courses->count(),
                ($studentIndex + 6) % $courses->count(),
            ];

            foreach ($assignedCourseIndexes as $courseIndex) {
                $course = $courses[$courseIndex];
                $completed = (($studentIndex + $courseIndex) % 4) === 0;

                $enrollment = Enrollment::query()->updateOrCreate([
                    'student_id' => $student->id,
                    'course_id' => $course->id,
                ], [
                    'enrolled_at' => Carbon::now()->subDays(45 - (($studentIndex + $courseIndex) % 20)),
                    'status' => $completed ? 'completed' : 'active',
                    'progress_percentage' => $completed ? 100 : (60 + (($studentIndex + $courseIndex) % 35)),
                    'completed_at' => $completed ? Carbon::now()->subDays(($studentIndex + $courseIndex) % 10) : null,
                ]);

                $quiz = $course->quizzes()->first();

                if (! $quiz) {
                    continue;
                }

                $baseScore = 60 + (($studentIndex + $courseIndex) % 41);

                QuizAttempt::query()->updateOrCreate([
                    'quiz_id' => $quiz->id,
                    'student_id' => $student->id,
                    'attempt_number' => 1,
                ], [
                    'enrollment_id' => $enrollment->id,
                    'started_at' => Carbon::now()->subDays(20 - (($studentIndex + $courseIndex) % 10)),
                    'submitted_at' => Carbon::now()->subDays(20 - (($studentIndex + $courseIndex) % 10))->addMinutes(22),
                    'score' => $baseScore,
                    'passed' => $baseScore >= $quiz->passing_score,
                    'status' => 'graded',
                ]);

                if ($baseScore < $quiz->passing_score && $completed) {
                    QuizAttempt::query()->updateOrCreate([
                        'quiz_id' => $quiz->id,
                        'student_id' => $student->id,
                        'attempt_number' => 2,
                    ], [
                        'enrollment_id' => $enrollment->id,
                        'started_at' => Carbon::now()->subDays(10 - (($studentIndex + $courseIndex) % 5)),
                        'submitted_at' => Carbon::now()->subDays(10 - (($studentIndex + $courseIndex) % 5))->addMinutes(18),
                        'score' => 78,
                        'passed' => true,
                        'status' => 'graded',
                    ]);
                }

                if ($completed) {
                    Certification::query()->updateOrCreate([
                        'enrollment_id' => $enrollment->id,
                    ], [
                        'student_id' => $student->id,
                        'course_id' => $course->id,
                        'certificate_number' => 'CC-' . str_pad((string) $enrollment->id, 6, '0', STR_PAD_LEFT),
                        'issued_at' => ($enrollment->completed_at ?? Carbon::now())->copy()->addDay(),
                        'valid_until' => null,
                        'status' => 'issued',
                        'file_path' => null,
                    ]);
                }
            }
        }
    }
}
