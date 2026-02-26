<?php

use App\Http\Controllers\Admin\LocaleController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function (): void {
    Route::get('/admin/locale/{locale}', [LocaleController::class, 'switch'])
        ->name('admin.locale.switch');
});

Route::get('/', function () {
    return response()->file(public_path('spa/home/index.html'));
});

Route::get('/courses/{path?}', function () {
    return response()->file(public_path('spa/courses/index.html'));
})->where('path', '.*');
