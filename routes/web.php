<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    if (Auth::guard('admin')->check()) {
        return redirect()->route('admin.dashboard');
    }
    if (Auth::guard('staff')->check()) {
        return redirect()->route('staff.dashboard');
    }
    if (Auth::guard('client')->check()) {
        return redirect()->route('client.dashboard');
    }
    return redirect()->route('client.login');
});

// ==========================================
// Unified Single Login Route
// ==========================================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Aliases for compatibility
Route::get('/login/client', [AuthController::class, 'showLogin'])->name('client.login');
Route::post('/login/client', [AuthController::class, 'login']);
Route::get('/login/staff', [AuthController::class, 'showLogin'])->name('staff.login');
Route::post('/login/staff', [AuthController::class, 'login']);
Route::get('/login/admin', [AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/login/admin', [AuthController::class, 'login']);

// ==========================================
// 2-Step OTP Account Activation & Password Setup
// ==========================================
Route::get('/activate-account', [AuthController::class, 'showActivationForm'])->name('account.activate');
Route::post('/activate-account/verify', [AuthController::class, 'verifyActivationOtp'])->name('account.activate.verify');
Route::get('/activate-account/set-password', [AuthController::class, 'showSetPasswordForm'])->name('account.activate.set-password');
Route::post('/activate-account/set-password', [AuthController::class, 'saveActivatedPassword'])->name('account.activate.save-password');

// ==========================================
// Forgot Password OTP Routes
// ==========================================
Route::get('/forgot-password', [App\Http\Controllers\ForgotPasswordController::class, 'showEmailForm'])->name('password.request');
Route::post('/forgot-password', [App\Http\Controllers\ForgotPasswordController::class, 'sendOtp'])->name('password.email');
Route::get('/verify-otp', [App\Http\Controllers\ForgotPasswordController::class, 'showOtpForm'])->name('password.verify.form');
Route::post('/verify-otp', [App\Http\Controllers\ForgotPasswordController::class, 'verifyOtp'])->name('password.verify');
Route::get('/reset-password', [App\Http\Controllers\ForgotPasswordController::class, 'showResetForm'])->name('password.reset.form');
Route::post('/reset-password', [App\Http\Controllers\ForgotPasswordController::class, 'resetPassword'])->name('password.reset');

// ==========================================
// 👑 ADMIN PORTAL ROUTES (/admin/*)
// ==========================================
Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
    
    // Clients & Staff Management
    Route::post('/clients/send-otp', [App\Http\Controllers\AdminClientController::class, 'sendCreationOtp'])->name('clients.send-otp');
    Route::resource('clients', App\Http\Controllers\AdminClientController::class);
    
    Route::post('/staff/send-otp', [App\Http\Controllers\AdminStaffController::class, 'sendCreationOtp'])->name('staff.send-otp');
    Route::resource('staff', App\Http\Controllers\AdminStaffController::class);
    
    // Services Catalog
    Route::resource('services', App\Http\Controllers\AdminServiceController::class);
    
    // Documents Desk
    Route::get('/documents', [App\Http\Controllers\AdminDocumentController::class, 'index'])->name('documents.index');
    Route::put('/documents/{id}', [App\Http\Controllers\AdminDocumentController::class, 'update'])->name('documents.update');
    Route::get('/documents/{id}/download', [App\Http\Controllers\AdminDocumentController::class, 'download'])->name('documents.download');
    
    // Tickets & Requests Desk
    Route::get('/tickets', [App\Http\Controllers\AdminTicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/{id}', [App\Http\Controllers\AdminTicketController::class, 'show'])->name('tickets.show');
    Route::put('/tickets/{id}/status', [App\Http\Controllers\AdminTicketController::class, 'update'])->name('tickets.update');
    Route::post('/tickets/{id}/reply', [App\Http\Controllers\AdminTicketController::class, 'reply'])->name('tickets.reply');
    
    // Notifications & Logs
    Route::get('/notifications', [App\Http\Controllers\AdminNotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/create', [App\Http\Controllers\AdminNotificationController::class, 'create'])->name('notifications.create');
    Route::post('/notifications', [App\Http\Controllers\AdminNotificationController::class, 'store'])->name('notifications.store');
    Route::get('/activity', [App\Http\Controllers\AdminActivityController::class, 'index'])->name('activity.index');
    
    // Analytics & Reporting
    Route::get('/reports', [App\Http\Controllers\AdminReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export/clients', [App\Http\Controllers\AdminReportController::class, 'exportClients'])->name('reports.export.clients');
    Route::get('/reports/export/services', [App\Http\Controllers\AdminReportController::class, 'exportServices'])->name('reports.export.services');

    // Admin Settings
    Route::get('/settings', [App\Http\Controllers\AdminSettingController::class, 'index'])->name('settings.index');
    Route::put('/settings/brand', [App\Http\Controllers\AdminSettingController::class, 'updateBrand'])->name('settings.brand');
    Route::put('/settings/company', [App\Http\Controllers\AdminSettingController::class, 'updateCompany'])->name('settings.company');
    Route::put('/settings/profile', [App\Http\Controllers\AdminSettingController::class, 'updateProfile'])->name('settings.profile');
    Route::put('/settings/password', [App\Http\Controllers\AdminSettingController::class, 'updatePassword'])->name('settings.password');
});

// ==========================================
// 💼 STAFF PORTAL ROUTES (/staff/*)
// ==========================================
Route::middleware(['auth:staff'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\StaffDashboardController::class, 'index'])->name('dashboard');
    
    // Assigned Clients (Staff can only view their assigned clients)
    Route::get('/clients', [App\Http\Controllers\StaffClientController::class, 'index'])->name('clients.index');
    Route::get('/clients/{client}', [App\Http\Controllers\StaffClientController::class, 'show'])->name('clients.show');
    
    // Services & Projects Management
    Route::get('/services', [App\Http\Controllers\StaffServiceController::class, 'index'])->name('services.index');
    Route::get('/services/create', [App\Http\Controllers\StaffServiceController::class, 'create'])->name('services.create');
    Route::post('/services', [App\Http\Controllers\StaffServiceController::class, 'store'])->name('services.store');
    Route::get('/services/{service}/edit', [App\Http\Controllers\StaffServiceController::class, 'edit'])->name('services.edit');
    Route::put('/services/{service}', [App\Http\Controllers\StaffServiceController::class, 'update'])->name('services.update');

    // Document Vault Review
    Route::get('/documents', [App\Http\Controllers\StaffDocumentController::class, 'index'])->name('documents.index');
    Route::put('/documents/{id}', [App\Http\Controllers\StaffDocumentController::class, 'update'])->name('documents.update');
    Route::get('/documents/{id}/download', [App\Http\Controllers\StaffDocumentController::class, 'download'])->name('documents.download');

    // Support Ticket Desk
    Route::get('/tickets', [App\Http\Controllers\StaffTicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/{ticket}', [App\Http\Controllers\StaffTicketController::class, 'show'])->name('tickets.show');
    Route::post('/tickets/{ticket}/reply', [App\Http\Controllers\StaffTicketController::class, 'reply'])->name('tickets.reply');
    
    // Staff Reports & Exports
    Route::get('/reports', [App\Http\Controllers\StaffReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export/clients', [App\Http\Controllers\StaffReportController::class, 'exportClients'])->name('reports.export.clients');
    Route::get('/reports/export/services', [App\Http\Controllers\StaffReportController::class, 'exportServices'])->name('reports.export.services');

    // Staff Profile & Password
    Route::get('/settings', [App\Http\Controllers\StaffProfileController::class, 'settings'])->name('settings');
    Route::post('/settings/password', [App\Http\Controllers\StaffProfileController::class, 'updatePassword'])->name('password.update');
});

// ==========================================
// 🏢 CLIENT PORTAL ROUTES (/client/*)
// ==========================================
Route::middleware(['auth:client'])->prefix('client')->name('client.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\ClientController::class, 'dashboard'])->name('dashboard');
    
    Route::get('/profile', [App\Http\Controllers\ClientProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [App\Http\Controllers\ClientProfileController::class, 'update'])->name('profile.update');
    Route::get('/settings', [App\Http\Controllers\ClientProfileController::class, 'settings'])->name('settings');
    Route::put('/settings/password', [App\Http\Controllers\ClientProfileController::class, 'updatePassword'])->name('settings.password');
    
    Route::get('/services', [App\Http\Controllers\ClientServiceController::class, 'index'])->name('services.index');
    
    Route::get('/documents', [App\Http\Controllers\ClientDocumentController::class, 'index'])->name('documents.index');
    Route::post('/documents', [App\Http\Controllers\ClientDocumentController::class, 'store'])->name('documents.store');
    Route::get('/documents/{id}/download', [App\Http\Controllers\ClientDocumentController::class, 'download'])->name('documents.download');
    Route::post('/documents/{id}/approve', [App\Http\Controllers\ClientDocumentController::class, 'approveDeliverable'])->name('documents.approve');
    Route::post('/documents/{id}/request-revision', [App\Http\Controllers\ClientDocumentController::class, 'requestRevision'])->name('documents.revision');
    
    Route::get('/tickets', [App\Http\Controllers\ClientTicketController::class, 'index'])->name('tickets.index');
    Route::post('/tickets', [App\Http\Controllers\ClientTicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/{id}', [App\Http\Controllers\ClientTicketController::class, 'show'])->name('tickets.show');
    Route::post('/tickets/{id}/reply', [App\Http\Controllers\ClientTicketController::class, 'reply'])->name('tickets.reply');
    
    Route::get('/knowledge-base', [App\Http\Controllers\ClientKnowledgeBaseController::class, 'index'])->name('knowledge.index');
    Route::get('/knowledge-base/{slug}', [App\Http\Controllers\ClientKnowledgeBaseController::class, 'show'])->name('knowledge.show');

    Route::get('/notifications', [App\Http\Controllers\ClientNotificationController::class, 'index'])->name('notifications.index');
    Route::put('/notifications/{id}/read', [App\Http\Controllers\ClientNotificationController::class, 'markAsRead'])->name('notifications.read');

    Route::get('/activity', [App\Http\Controllers\ClientActivityController::class, 'index'])->name('activity.index');
});
