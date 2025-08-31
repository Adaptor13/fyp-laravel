<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CaseController;
use App\Http\Controllers\CaseHistoryController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ContactQueryController;
use App\Http\Controllers\AdminContactQueryController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\CwoProfileController;
use App\Http\Controllers\HealthcareProfileController;
use App\Http\Controllers\LawEnforcementProfileController;
use App\Http\Controllers\SocialWorkerProfileController;

Route::middleware('web')->group(function () {

    Route::get('/sign_in', [AuthController::class, 'signIn'])->name('sign_in');
    Route::get('/sign_up', [AuthController::class, 'signUp'])->name('sign_up');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::get('/login', function () {
        return redirect()->route('sign_in');
    });
    
     // Password reset
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])
        ->middleware('guest')
        ->name('password.email');

    Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])
        ->middleware('guest')
        ->name('password.reset');

    Route::post('/reset-password', [AuthController::class, 'resetPassword'])
        ->middleware('guest')
        ->name('password.update');

    // Auth-required
    Route::middleware('auth')->group(function () {
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile/delete', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });

    // Admin Dashboard - accessible by all authenticated users with appropriate roles
    Route::middleware(['auth', 'role:admin,social_worker,law_enforcement,healthcare,gov_official,public_user'])->group(function () {
        Route::get('/index', [AdminController::class, 'index'])->name('admin_index');
        Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    });



    // Admin-only routes
    Route::middleware(['auth', 'role:admin'])->group(function () {

        // Contact Queries Management
        Route::get('/contact-queries', [AdminContactQueryController::class, 'index'])
            ->name('admin.contact-queries.index')
            ->middleware('permission:contact_queries.view');
        Route::get('/contact-queries/data', [AdminContactQueryController::class, 'getData'])
            ->name('admin.contact-queries.data')
            ->middleware('permission:contact_queries.view');
        Route::get('/contact-queries/export', [AdminContactQueryController::class, 'export'])
            ->name('admin.contact-queries.export')
            ->middleware('permission:contact_queries.export');
        Route::get('/contact-queries/{id}', [AdminContactQueryController::class, 'show'])
            ->name('admin.contact-queries.show')
            ->middleware('permission:contact_queries.view');
        Route::post('/contact-queries/{id}/status', [AdminContactQueryController::class, 'updateStatus'])
            ->name('admin.contact-queries.update-status')
            ->middleware('permission:contact_queries.edit');
        Route::delete('/contact-queries/{id}', [AdminContactQueryController::class, 'destroy'])
            ->name('admin.contact-queries.destroy')
            ->middleware('permission:contact_queries.delete');

        // Session Logs Management
        Route::get('/activity-logs', [ActivityLogController::class, 'index'])
            ->name('admin.activity-logs.index')
            ->middleware('permission:activity_logs.view');
        Route::get('/activity-logs/data', [ActivityLogController::class, 'getData'])
            ->name('admin.activity-logs.data')
            ->middleware('permission:activity_logs.view');
        Route::get('/activity-logs/export/csv', [ActivityLogController::class, 'exportCSV'])
            ->name('admin.activity-logs.export-csv')
            ->middleware('permission:activity_logs.export');
        Route::get('/activity-logs/filter-options', [ActivityLogController::class, 'getFilterOptions'])
            ->name('admin.activity-logs.filter-options')
            ->middleware('permission:activity_logs.view');

        Route::get('/users/admins', [UserController::class, 'admins'])->name('users.admins');

        // Admin Profile Management
        Route::get('/admin/profile/edit', [AdminProfileController::class, 'edit'])
            ->name('admin.profile.edit');
        Route::put('/admin/profile/update', [AdminProfileController::class, 'update'])
            ->name('admin.profile.update');
        Route::delete('/admin/profile/delete', [AdminProfileController::class, 'destroy'])
            ->name('admin.profile.destroy');

        // CWO Profile Management
        Route::get('/cwo/profile/edit', [CwoProfileController::class, 'edit'])
            ->name('cwo.profile.edit');
        Route::put('/cwo/profile/update', [CwoProfileController::class, 'update'])
            ->name('cwo.profile.update');
        Route::delete('/cwo/profile/delete', [CwoProfileController::class, 'destroy'])
            ->name('cwo.profile.destroy');

        // Healthcare Profile Management
        Route::get('/healthcare/profile/edit', [HealthcareProfileController::class, 'edit'])
            ->name('healthcare.profile.edit');
        Route::put('/healthcare/profile/update', [HealthcareProfileController::class, 'update'])
            ->name('healthcare.profile.update');
        Route::delete('/healthcare/profile/delete', [HealthcareProfileController::class, 'destroy'])
            ->name('healthcare.profile.destroy');

        // Law Enforcement Profile Management
        Route::get('/law/profile/edit', [LawEnforcementProfileController::class, 'edit'])
            ->name('law.profile.edit');
        Route::put('/law/profile/update', [LawEnforcementProfileController::class, 'update'])
            ->name('law.profile.update');
        Route::delete('/law/profile/delete', [LawEnforcementProfileController::class, 'destroy'])
            ->name('law.profile.destroy');

        // Social Worker Profile Management
        Route::get('/social/profile/edit', [SocialWorkerProfileController::class, 'edit'])
            ->name('social.profile.edit');
        Route::put('/social/profile/update', [SocialWorkerProfileController::class, 'update'])
            ->name('social.profile.update');
        Route::delete('/social/profile/delete', [SocialWorkerProfileController::class, 'destroy'])
            ->name('social.profile.destroy');

        // Admins
        Route::get('/users/admin/data', [UserController::class, 'adminData'])
            ->name('users.admin.data');

        Route::post('/users/admin', [UserController::class, 'storeAdmin'])
            ->name('users.admin.store')
            ->middleware('permission:users.create');

        Route::put('/users/admin/{user}', [UserController::class, 'updateAdmin'])
            ->name('users.admin.update')
            ->middleware('permission:users.edit');

        Route::delete('/users/admin/{user}', [UserController::class, 'destroyAdmin'])
            ->name('users.admin.destroy')
            ->middleware('permission:users.delete');

        //Law
        Route::get('/users/law-enforcement',[UserController::class, 'lawEnforcement'])->name('users.law');

        Route::get('/users/law/data', [UserController::class, 'lawEnforcementData'])
            ->name('users.law.data');

        Route::post('/users/law', [UserController::class, 'storeLawEnforcement'])
            ->name('users.law.store')
            ->middleware('permission:users.create');

        Route::put('/users/law/{user}', [UserController::class, 'updateLawEnforcement'])
            ->name('users.law.update')
            ->middleware('permission:users.edit');

        Route::delete('/users/law/{user}', [UserController::class, 'destroyLawEnforcement'])
            ->name('users.law.destroy')
            ->middleware('permission:users.delete');

        // Government Child Welfare Officials (CWO)
        Route::get('/users/cwo', [UserController::class, 'cwo'])->name('users.cwo');

        Route::get('/users/cwo/data', [UserController::class, 'childWelfareOfficerData'])
            ->name('users.cwo.data');

        Route::post('/users/cwo', [UserController::class, 'storeCwo'])
            ->name('users.cwo.store')
            ->middleware('permission:users.create');

        Route::put('/users/cwo/{user}', [UserController::class, 'updateCwo'])
            ->name('users.cwo.update')
            ->middleware('permission:users.edit');

        Route::delete('/users/cwo/{user}', [UserController::class, 'destroyCwo'])
            ->name('users.cwo.destroy')
            ->middleware('permission:users.delete');

        //Public User
        Route::get('/users/public-users',  [UserController::class, 'publicUsers'])->name('users.public');

        Route::get('/users/public-users/data', [UserController::class, 'publicUsersData'])
            ->name('users.public.data');

        Route::post('/users/public-users', [UserController::class, 'storePublic'])
            ->name('users.public.store')
            ->middleware('permission:users.create');

        Route::put('/users/public-users/{id}', [UserController::class, 'updatePublicUser'])
            ->name('users.public.update')
            ->middleware('permission:users.edit');

        Route::delete('/users/public-users/{id}', [UserController::class, 'destroyPublicUser'])
            ->name('users.public.destroy')
            ->middleware('permission:users.delete');

        // Social Worker
        Route::get('/users/social-workers',[UserController::class, 'socialWorkers'])->name('users.social');

        Route::get('/users/social-workers/data', [UserController::class, 'socialWorkersData'])
            ->name('users.social.data');

        Route::post('/users/social-workers', [UserController::class, 'storeSocialWorker'])
            ->name('users.social.store')
            ->middleware('permission:users.create');

        
        Route::put('/users/social-workers/{user}', [UserController::class, 'updateSocialWorker'])
            ->name('users.social.update')
            ->middleware('permission:users.edit');

        Route::delete('/users/social/{user}', [UserController::class, 'destroySocialWorker'])
            ->name('users.social.destroy')
            ->middleware('permission:users.delete');

        
        //Healthcare
        Route::get('/users/healthcare',    [UserController::class, 'healthcare'])->name('users.health');

        Route::get('/users/healthcare/data', [UserController::class, 'healthcareData'])
            ->name('users.healthcare.data');

        Route::post('/users/healthcare', [UserController::class, 'storeHealthcare'])
            ->name('users.healthcare.store')
            ->middleware('permission:users.create');

        Route::put('/users/healthcare/{user}', [UserController::class, 'updateHealthcare'])
            ->name('users.healthcare.update')
            ->middleware('permission:users.edit');

        Route::delete('/users/healthcare/{user}', [UserController::class, 'destroyHealthcare'])
            ->name('users.healthcare.destroy')
            ->middleware('permission:users.delete');

        // Roles Management
        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
        Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
        Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
        Route::get('/roles/data', [RoleController::class, 'getData'])->name('roles.data');
        Route::get('/roles/{role}', [RoleController::class, 'show'])->name('roles.show');
        Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
        Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
        Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
        Route::get('/roles/{role}/permissions', [RoleController::class, 'assignPermissions'])->name('roles.assign-permissions');
        Route::put('/roles/{role}/permissions', [RoleController::class, 'updatePermissions'])->name('roles.update-permissions');

        // Permissions Management
        Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
        Route::get('/permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
        Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
        Route::get('/permissions/{permission}', [PermissionController::class, 'show'])->name('permissions.show');
        Route::get('/permissions/{permission}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
        Route::put('/permissions/{permission}', [PermissionController::class, 'update'])->name('permissions.update');
        Route::delete('/permissions/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy');
        Route::get('/permissions/data', [PermissionController::class, 'getData'])->name('permissions.data');

        // Dashboard Export Routes - CSV
        Route::get('/dashboard/export/cases/csv', [AdminController::class, 'exportCasesCSV'])
            ->name('admin.dashboard.export.cases.csv')
            ->middleware('permission:dashboard.export');
        Route::get('/dashboard/export/users/csv', [AdminController::class, 'exportUsersCSV'])
            ->name('admin.dashboard.export.users.csv')
            ->middleware('permission:dashboard.export');
        Route::get('/dashboard/export/contact-queries/csv', [AdminController::class, 'exportContactQueriesCSV'])
            ->name('admin.dashboard.export.contact-queries.csv')
            ->middleware('permission:dashboard.export');

        // Dashboard Export Routes - PDF
        Route::get('/dashboard/export/cases/pdf', [AdminController::class, 'exportCasesPDF'])
            ->name('admin.dashboard.export.cases.pdf')
            ->middleware('permission:dashboard.export');
        Route::get('/dashboard/export/users/pdf', [AdminController::class, 'exportUsersPDF'])
            ->name('admin.dashboard.export.users.pdf')
            ->middleware('permission:dashboard.export');
        Route::get('/dashboard/export/contact-queries/pdf', [AdminController::class, 'exportContactQueriesPDF'])
            ->name('admin.dashboard.export.contact-queries.pdf')
            ->middleware('permission:dashboard.export');

    });

    Route::middleware(['auth', 'role:admin,social_worker,law_enforcement,healthcare,gov_official'])
        ->group(function () {
         Route::get('/cases/data', [CaseController::class, 'reportData'])->name('cases.data');

        // View Cases + Case Details + inline actions (notes/status)
        Route::get('/cases', [CaseController::class, 'index'])->name('cases.index');
        Route::get('/cases/{report}', [CaseController::class, 'show'])->name('cases.show');
        Route::post('/cases/{report}/note', [CaseController::class, 'addNote'])->name('cases.note');
        Route::post('/cases/{report}/status',[CaseController::class, 'updateStatus'])->name('cases.status');

        // Cases CRUD routes
        Route::post('/cases', [CaseController::class, 'store'])->name('cases.store')->middleware('permission:cases.create');
        Route::get('/cases/{report}/edit', [CaseController::class, 'edit'])->name('cases.edit')->middleware('permission:cases.edit');
        Route::put('/cases/{report}', [CaseController::class, 'update'])->name('cases.update')->middleware('permission:cases.edit');
        Route::delete('/cases/{report}', [CaseController::class, 'destroy'])->name('cases.destroy')->middleware('permission:cases.delete');
        Route::get('/cases/{report}/export', [CaseController::class, 'export'])->name('cases.export');

        // Case History routes
        Route::get('/cases/{report}/history', [CaseHistoryController::class, 'show'])->name('cases.history');
        Route::get('/cases/{report}/history/json', [CaseHistoryController::class, 'getHistory'])->name('cases.history.json');

        // Case Messaging routes
        Route::get('/cases/{case}/messages', [App\Http\Controllers\CaseMessageController::class, 'index'])->name('cases.messages.index');
        Route::post('/cases/{case}/messages', [App\Http\Controllers\CaseMessageController::class, 'store'])->name('cases.messages.store');
    });

    // Route::middleware(['auth', 'role:admin,gov_official'])
    //     ->group(function () {
    //     Route::get('/cases/assign', [AssignmentController::class, 'create'])->name('cases.assign');
    //     Route::post('/cases/{report}/assign',[AssignmentController::class, 'store'])->name('cases.assign.store');
    // });

    // Landing Page
    Route::get('/', [LandingController::class, 'landing'])->name('landing');
    Route::get('/report', [LandingController::class, 'report'])->name('report');
    Route::post('/report', [ReportController::class, 'store'])->name('report.store');
    
    // Contact Queries
    Route::get('/contact', [ContactQueryController::class, 'show'])->name('contact.show');
    Route::post('/contact', [ContactQueryController::class, 'store'])->name('contact.store');
    
    // My Reports route for logged-in users
    Route::middleware('auth')->group(function () {
        Route::get('/my-reports', [ReportController::class, 'myReports'])->name('reports.track');
        Route::get('/my-reports/{report}/export', [ReportController::class, 'export'])->name('reports.export');
    });

});
