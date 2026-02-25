<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'device_name' => ['nullable', 'string', 'max:255'],
        ]);

        $user = User::query()
            ->where('email', $credentials['email'])
            ->with(['roles', 'studentProfile'])
            ->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Невірний email або пароль.'],
            ]);
        }

        if (! $user->studentProfile || ! $user->studentProfile->is_active) {
            return response()->json([
                'message' => 'Доступ до студентського кабінету недоступний для цього користувача.',
            ], 403);
        }

        $accessToken = $user->createToken($credentials['device_name'] ?? 'student-spa')->accessToken;

        return response()->json([
            'token_type' => 'Bearer',
            'access_token' => $accessToken,
            'user' => $this->transformUser($user),
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user()->load(['roles', 'studentProfile']);

        return response()->json([
            'user' => $this->transformUser($user),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $token = $request->user()?->token();
        $token?->revoke();

        return response()->json([
            'message' => 'Сесію завершено.',
        ]);
    }

    public function updateProfile(Request $request): JsonResponse
    {
        $user = $request->user()->load('studentProfile');
        $student = $user->studentProfile;

        if (! $student) {
            return response()->json([
                'message' => 'Студентський профіль не знайдено.',
            ], 403);
        }

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->id),
                Rule::unique('students', 'email')->ignore($student->id),
            ],
            'phone' => ['nullable', 'string', 'max:50'],
        ]);

        $fullName = trim("{$data['first_name']} {$data['last_name']}");

        $user->forceFill([
            'name' => $fullName,
            'email' => $data['email'],
        ])->save();

        $student->forceFill([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
        ])->save();

        return response()->json([
            'message' => 'Профіль оновлено.',
            'user' => $this->transformUser($user->fresh()->load(['roles', 'studentProfile'])),
        ]);
    }

    private function transformUser(User $user): array
    {
        $student = $user->studentProfile;

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'roles' => $user->roles->pluck('name')->values()->all(),
            'student' => $student ? [
                'id' => $student->id,
                'first_name' => $student->first_name,
                'last_name' => $student->last_name,
                'full_name' => $student->full_name,
                'email' => $student->email,
                'phone' => $student->phone,
                'external_id' => $student->external_id,
                'is_active' => $student->is_active,
            ] : null,
        ];
    }
}
