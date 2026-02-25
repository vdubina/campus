<?php

use App\Http\Controllers\Admin\LocaleController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function (): void {
    Route::get('/admin/locale/{locale}', [LocaleController::class, 'switch'])
        ->name('admin.locale.switch');
});

Route::get('/{path?}', function () {
    return response()->file(public_path('spa/index.html'));
})->where('path', '^(?!(admin|api)(?:/|$)).*');
