<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\JobApplicationController;
use App\Http\Controllers\admin\JobController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/jobs', [JobsController::class,'index'])->name('jobs');

Route::get('/jobs/detail/{id}', [JobsController::class,'detail'])->name('detail');

Route::post('/apply-job', [JobsController::class,'applyJob'])->name('applyJob');

Route::post('/save-job', [JobsController::class,'saveJob'])->name('saveJob');

Route::get('/forgot-password', [AccountController::class, 'forgotPassword'])->name('account.forgotPassword');

Route::post('/process-forgot-password', [AccountController::class, 'processForgotPassword'])->name('account.processForgotPassword');

Route::get('/process-reset-password', [AccountController::class, 'processResetPassword'])->name('account.processResetPassword');

Route::post('/reset-password/{token}', [AccountController::class, 'resetPassword'])->name('account.resetPassword');

Route::group(['prefix' => 'admin', 'middleware' => 'checkRole'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('/users', [UserController::class, 'index'])->name('admin.users');

    Route::get('/users/{id}', [UserController::class, 'edit'])->name('admin.users.edit');

    Route::put('/users/{id}', [UserController::class, 'updateUser'])->name('admin.users.updateuser');

    Route::delete('/users', [UserController::class, 'destroy'])->name('admin.users.destroy');

    Route::get('/jobs', [JobController::class, 'index'])->name('admin.jobs');

    Route::get('/jobs/edit/{id}', [JobController::class, 'edit'])->name('admin.jobs.edit');

    Route::put('/jobs/{id}', [JobController::class, 'update'])->name('admin.jobs.update');

    Route::delete('/jobs', [JobController::class, 'destroy'])->name('admin.jobs.destroy');

    Route::get('/job-applications', [JobApplicationController::class, 'index'])->name('admin.jobApplications');

    Route::delete('/job-applications', [JobApplicationController::class, 'destroy'])->name('admin.jobApplications.destroy');
});

Route::group(['account'], function () {

    //Guest Role->Uses guest middleware (RedirectIfAuthenticated)
    //If user logged in == yes and it tries to access login or register page it will redirect user to profile page
    Route::group(['middleware' => 'guest'], function () {

        Route::get('/register', [AccountController::class, 'register'])->name('register');

        Route::post('/process-register', [AccountController::class, 'processRegister'])->name('processRegister');
        
        Route::get('/login', [AccountController::class, 'login'])->name('login');

        Route::post('/authenticate', [AccountController::class, 'authenticate'])->name('authenticate');
    });

    //Authenticated Routes->Uses auth middleware (Authenticate)
    //If user logged in == no and it tries to access login profile page it will redirect user to login page
    Route::group(['middleware' => 'auth'], function () {

        Route::get('/profile', [AccountController::class, 'profile'])->name('profile');

        Route::put('/update-profile', [AccountController::class, 'updateProfile'])->name('updateProfile');

        Route::get('/logout', [AccountController::class, 'logout'])->name('logout');

        Route::post('/update-profile-pic', [AccountController::class, 'updateProfilePicture'])->name('updateProfilePicture');

        Route::get('/create-job', [AccountController::class, 'createJob'])->name('createJob');
    
        Route::post('/save-job', [AccountController::class, 'saveJob'])->name('saveJob');
    
        Route::get('/my-jobs', [AccountController::class, 'myJobs'])->name('myJobs');
    
        Route::get('/my-jobs/edit/{jobId}', [AccountController::class, 'editJob'])->name('editJob');
    
        Route::post('/update-job/{jobId}', [AccountController::class, 'updateJob'])->name('updateJob');

        Route::post('/delete-job', [AccountController::class, 'deleteeJob'])->name('deleteeJob');

        Route::get('/my-job-applications', [AccountController::class, 'myJobApplications'])->name('myJobApplications');

        Route::post('/remove-job-application', [AccountController::class, 'removeJobs'])->name('removeJobs');

        Route::get('/saved-jobs', [AccountController::class, 'savedJobs'])->name('savedJobs');

        Route::post('/remove-saved-jobs', [AccountController::class,'removeSavedJob'])->name('removeSavedJob');

        Route::post('/update-password', [AccountController::class, 'updatePassword'])->name('updatePassword');
    });
});
