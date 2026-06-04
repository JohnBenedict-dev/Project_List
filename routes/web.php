<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserController;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Route;

// Put these right below your project route definitions:
Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update')->middleware('auth');
Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy')->middleware('auth');

// Main Welcome Page Route (Loads real database records)
Route::get('/', function () {
    $projects = Project::all(); 
    return view('welcome', compact('projects'));
});

// Secure Dashboard Route (Loads table data AND real counter metrics)
Route::get('/dashboard', function () {
    $projects = Project::all(); 
    $users = User::all(); // Fetch all registered users from the database
    
    // Dynamically calculate the totals for your metric cards
    $totalUsers = $users->count(); 
    $totalProjects = $projects->count();
    $pendingProjects = $projects->where('status', 'Pending')->count();
    $completedProjects = $projects->where('status', 'Completed')->count();
    
    // Pass the $users variable cleanly into the blade template
    return view('dashboard', compact('projects', 'users', 'totalUsers', 'totalProjects', 'pendingProjects', 'completedProjects'));
})->middleware(['auth', 'verified'])->name('dashboard');

// Form Submit Action Route for Adding New Projects
Route::post('/projects', [ProjectController::class, 'store'])->name('project.store');

// Profile Settings Routes (Cleaned up & updated to PUT)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    
    // Changed from PATCH to PUT to perfectly match your form submission
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';