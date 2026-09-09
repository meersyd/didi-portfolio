<?php

use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EducationController;
use App\Http\Controllers\Admin\ExperienceController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\SiteCopyController;
use App\Http\Controllers\Admin\SkillController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ResumeController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/projects/{project:slug}', [ProjectController::class, 'show'])->name('projects.show');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/resume', ResumeController::class)->name('resume');
Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:contact')
    ->name('contact.store');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])
        ->middleware('throttle:login')
        ->name('login.store');
});

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::get('pages', [SiteCopyController::class, 'edit'])->name('pages.edit');
    Route::put('pages', [SiteCopyController::class, 'update'])->name('pages.update');
    Route::resource('projects', AdminProjectController::class)->except('show');
    Route::resource('experiences', ExperienceController::class)->except('show');
    Route::resource('skills', SkillController::class)->except('show');
    Route::resource('education', EducationController::class)
        ->parameters(['education' => 'education'])
        ->except('show');
    Route::get('messages', [ContactMessageController::class, 'index'])->name('messages.index');
    Route::get('messages/{message}', [ContactMessageController::class, 'show'])->name('messages.show');
    Route::patch('messages/{message}', [ContactMessageController::class, 'update'])->name('messages.update');
    Route::delete('messages/{message}', [ContactMessageController::class, 'destroy'])->name('messages.destroy');
});
