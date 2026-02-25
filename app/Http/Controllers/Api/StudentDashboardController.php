<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Quiz;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentDashboardController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user()->load('studentProfile');
        $student = $user->studentProfile;

        if (! $student || ! $student->is_active) {
            return response()->json([
                'message' => 'Студентський профіль не знайдено або деактивовано.',
            ], 403);
        }

        $enrollments = Enrollment::query()
            ->where('student_id', $student->id)
            ->with([
                'course.topics',
                'course.quizzes.questions.answerOptions',
                'course.instructor',
                'certification',
            ])
            ->orderByDesc('enrolled_at')
            ->get();

        return response()->json([
            'student' => [
                'id' => $student->id,
                'first_name' => $student->first_name,
                'last_name' => $student->last_name,
                'full_name' => $student->full_name,
                'email' => $student->email,
                'phone' => $student->phone,
                'external_id' => $student->external_id,
            ],
            'courses' => $enrollments->map(function (Enrollment $enrollment): array {
                $course = $enrollment->course;

                return [
                    'enrollment_id' => $enrollment->id,
                    'id' => $course->id,
                    'title' => $course->title,
                    'description' => $course->description,
                    'status' => $enrollment->status,
                    'progress_percentage' => $enrollment->progress_percentage,
                    'enrolled_at' => optional($enrollment->enrolled_at)->toIso8601String(),
                    'completed_at' => optional($enrollment->completed_at)->toIso8601String(),
                    'instructor' => $course->instructor ? [
                        'full_name' => trim("{$course->instructor->first_name} {$course->instructor->last_name}"),
                        'email' => $course->instructor->email,
                    ] : null,
                    'topics' => $course->topics->map(fn ($topic): array => [
                        'id' => $topic->id,
                        'title' => $topic->title,
                        'description' => $topic->description,
                        'position' => $topic->position,
                    ])->values()->all(),
                    'quizzes' => $course->quizzes->map(fn (Quiz $quiz): array => [
                        'id' => $quiz->id,
                        'title' => $quiz->title,
                        'description' => $quiz->description,
                        'passing_score' => $quiz->passing_score,
                        'time_limit_minutes' => $quiz->time_limit_minutes,
                        'max_attempts' => $quiz->max_attempts,
                        'is_published' => $quiz->is_published,
                        'questions' => $quiz->questions->map(function ($question): array {
                            $options = $question->answerOptions->pluck('option_text')->values();
                            $correctIndex = $question->answerOptions->search(fn ($option): bool => (bool) $option->is_correct);

                            return [
                                'id' => $question->id,
                                'text' => $question->question_text,
                                'options' => $options->all(),
                                'correct_answer_index' => $correctIndex === false ? null : $correctIndex,
                            ];
                        })->values()->all(),
                    ])->values()->all(),
                    'certification' => $enrollment->certification ? [
                        'id' => $enrollment->certification->id,
                        'certificate_number' => $enrollment->certification->certificate_number,
                        'issued_at' => optional($enrollment->certification->issued_at)->toIso8601String(),
                        'status' => $enrollment->certification->status,
                    ] : null,
                ];
            })->values()->all(),
        ]);
    }
}
