<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// ---------- Models ----------
use App\Models\Blog;
use App\Models\Report;

// ---------- Auth ----------
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

// ---------- Profile / User ----------
use App\Http\Controllers\ProfileController;

// ---------- Public / Pages ----------
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\PublicPackageController;
use App\Http\Controllers\HospitalReferralController;
use App\Http\Controllers\FaqController;

// ---------- Appointments ----------
use App\Http\Controllers\AppointmentController;

// ---------- Dashboard / Notifications ----------
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationsController;

// ---------- Admin ----------
use App\Http\Controllers\Admin\PackageController as AdminPackageController;
use App\Http\Controllers\Admin\LabTestController;
use App\Http\Controllers\Admin\BannerController as AdminBannerController;
use App\Http\Controllers\Admin\InquiryController as AdminInquiryController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\HospitalController;
use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\Admin\FaqAdminController;
use App\Http\Controllers\Admin\ReminderController;

// ---------- Commerce ----------
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomPackageController;

// ---------- Optional ----------
use App\Http\Controllers\SearchController;

// ---------- Coupons (public + admin) ----------
use App\Http\Controllers\Checkout\CouponApplyController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;

// ---------- Easebuzz Payment Gateway ----------
use App\Http\Controllers\EasebuzzController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportWhatsappController;
use App\Http\Controllers\WhatsappController;
use App\Http\Controllers\AppointmentWhatsappController;

// ---------- Hospital Owner (new) ----------
use App\Http\Controllers\Hospital\HospitalDashboardController;
use App\Http\Controllers\Hospital\HospitalAppointmentController;
use App\Http\Controllers\Hospital\ProfileController as HospitalProfileController;
use App\Http\Controllers\BlogController;
/*
|--------------------------------------------------------------------------
| Web Routes (merged)
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Auth / Registration (guest)
|--------------------------------------------------------------------------
*/

Route::get('/register', [RegisteredUserController::class, 'create'])
    ->name('register')
    ->middleware('guest');

Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware('guest');

// Logout
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

/*
|--------------------------------------------------------------------------
| Utility (CSRF refresh for long-open pages)
|--------------------------------------------------------------------------
*/
Route::get('/refresh-csrf', function () {
    return response()->json([
        'token' => csrf_token()
    ]);
});



/*
|--------------------------------------------------------------------------
| Public / Landing / Pages
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

// Static pages
Route::get('/about_us', function () {
    $blogs = Blog::where('status', 'published')
        ->latest()
        ->take(3) // number of blogs on About Us page
        ->get();

    $page = \App\Models\Page::whereIn('slug', ['about_us', 'about-us'])->first();

    return view('about_us', compact('blogs', 'page'));
})->name('about_us');

Route::get('/contact_us', function () {
    $page = \App\Models\Page::whereIn('slug', ['contact_us', 'contact-us'])->first();
    return view('contact_us', compact('page'));
})->name('contact_us');

Route::get('/awards_certificates', function () {
    $page = \App\Models\Page::whereIn('slug', ['awards_certificates', 'awards-certificates'])->first();
    return view('awards_certificates', compact('page'));
})->name('awards_certificates');

Route::get('/partner_with_us', function () {
    $page = \App\Models\Page::whereIn('slug', ['partner_with_us', 'partner-with-us'])->first();
    return view('partner_with_us', compact('page'));
})->name('partner_with_us');

Route::get('/founder', function () {
    $page = \App\Models\Page::where('slug', 'founder')->first();
    return view('founder', compact('page'));
})->name('founder');

// Public Dynamic Service Pages Catalog & Detail Routes
Route::get('/services', [\App\Http\Controllers\PageController::class, 'index'])->name('pages.index');
Route::get('/service/{page:slug}', [\App\Http\Controllers\PageController::class, 'show'])->name('pages.show');

// Core Service Pages (Direct High-Priority SEO Routes)
Route::get('/best-pathology-lab-in-pune', function () {
    $page = \App\Models\Page::whereIn('slug', ['best-pathology-lab-in-pune', 'pathology-lab-pune'])->first();
    return view('services.best_pathology_lab_pune', compact('page'));
})->name('services.best_pathology_lab');

Route::get('/blood-test-booking-in-pune', function () {
    $page = \App\Models\Page::whereIn('slug', ['blood-test-booking-in-pune', 'blood-test-booking'])->first();
    return view('services.blood_test_booking_pune', compact('page'));
})->name('services.blood_test_booking');

Route::get('/health-checkup-packages-in-pune', function () {
    $page = \App\Models\Page::whereIn('slug', ['health-checkup-packages-in-pune', 'health-checkup-packages'])->first();
    return view('services.health_checkup_packages_pune', compact('page'));
})->name('services.health_checkup_packages');

// Services (routes)
Route::get('/tests', [ServiceController::class, 'index'])->name('services');
Route::get('/test/{labTest:slug}', [ServiceController::class, 'show'])->name('services.show');
Route::get('/paymentSuccess', [PaymentController::class, 'paymentSuccess'])->name('payments.success');
Route::get('/paymentFailed', [PaymentController::class, 'paymentFailed'])->name('payment.failed');

Route::get('/whatsapp/test', [WhatsappController::class, 'testSend']);
Route::get('/whatsapp/test-text', [WhatsappController::class, 'testText']);
Route::get('/whatsapp/test-template', [WhatsappController::class, 'testTemplate']);
Route::post('/admin/appointments/{appointment}/share-whatsapp', [AppointmentWhatsappController::class, 'send'])
    ->name('appointments.share-whatsapp');

Route::get('/whatsapp-media/report/{report}', function (Report $report) {
    $path = storage_path('app/public/' . $report->report_file);

    abort_unless(file_exists($path), 404);

    return response()->file($path, [
        'Content-Type'  => 'application/pdf',
        'Cache-Control' => 'public, max-age=3600',
    ]);
})->name('whatsapp.report.media');

Route::view('/privacy_policy_and_terms_and_condition', 'privacy_policy_and_terms_and_condition')
    ->name('privacy.terms');

Route::get('/faq', [FaqController::class, 'index'])->name('faq');

// Booking page (public) + submission
Route::get('/booking', [AppointmentController::class, 'create'])->name('booking.create');
Route::post('/submit-appointment', [EasebuzzController::class, 'submitForm'])->name('form.submit');
// Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store'); 

// Packages listing + details + special + active API
if (class_exists(PublicPackageController::class)) {
    // Special page MUST be before slug route
    if (method_exists(PublicPackageController::class, 'special')) {
        Route::get('/packages/special', [PublicPackageController::class, 'special'])->name('packages.special');
        Route::get('/packages/special/{package:slug}', [PublicPackageController::class, 'show'])->name('packages.special.show');
    }

    Route::get('/packages', [PublicPackageController::class, 'index'])->name('packages.index');
    Route::get('/packages/{package:slug}', [PublicPackageController::class, 'show'])->name('packages.show');

    // Active packages API (public)
    if (method_exists(PublicPackageController::class, 'active')) {
        Route::get('/api/packages/active', [PublicPackageController::class, 'active'])->name('api.packages.active');
    }

    // Fetch ALL packages for front-end search (AJAX)
    if (method_exists(PublicPackageController::class, 'searchAll')) {
        Route::get('/packages/search/all', [PublicPackageController::class, 'searchAll'])->name('packages.search.all');
    }
}


// ---------- Blogs (Frontend) ----------
Route::get('/blogs', [BlogController::class, 'index'])->name('blogs.index');
Route::get('/blogs/category/{categorySlug}', [BlogController::class, 'index'])->name('blogs.category');

Route::get('/blogs/{blog:slug}', [BlogController::class, 'show'])->name('blogs.show');


// Active banners API
Route::get('/api/banners/active', [HomeController::class, 'activeBanners'])->name('api.banners.active');

// Inquiries (contact form)
Route::post('/inquiry', [AdminInquiryController::class, 'store'])->name('inquiry.store');

// Public hospital referral (QR)
Route::get('/h/{uniqueId}', [HospitalReferralController::class, 'redirect'])->name('hospital.ref');

/*
|--------------------------------------------------------------------------
| Easebuzz Payment Gateway
|--------------------------------------------------------------------------
*/
Route::post('/checkout/pay', [EasebuzzController::class, 'payFromCheckout'])->name('checkout.pay');

// Payment history pages
Route::get('/payments/history', [PaymentController::class, 'index'])->name('payments.history');
Route::get('/appointments/{appointment}/payments', [PaymentController::class, 'showForAppointment'])
    ->whereNumber('appointment')
    ->name('payments.forAppointment');

// Easebuzz callbacks and redirects - accept GET and POST
Route::match(['get', 'post'], '/easebuzz/success', [EasebuzzController::class, 'success'])->name('easebuzz.success');
Route::match(['get', 'post'], '/easebuzz/failure', [EasebuzzController::class, 'failure'])->name('easebuzz.failure');

// Server-to-server webhook (POST only)
Route::post('/easebuzz/ebz_response', [EasebuzzController::class, 'ebz_response'])->name('easebuzz.ebz_response');

/*
|--------------------------------------------------------------------------
| Customize Package
|--------------------------------------------------------------------------
*/
Route::get('/customize-package', [CustomPackageController::class, 'index'])->name('customize.index');
Route::post('/customize-package/create', [CustomPackageController::class, 'store'])->name('customize.store');

/* cash Suucesss page route  */
Route::get('/cash-success/{appointment}', function (\App\Models\Appointment $appointment) {
    return view('payments.cash-success', compact('appointment'));
})->name('cash.success');

/*
|--------------------------------------------------------------------------
| Cart
|--------------------------------------------------------------------------
*/
Route::prefix('cart')->group(function () {
    Route::post('/add', [CartController::class, 'add'])->name('cart.add');
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::get('/count', [CartController::class, 'count'])->name('cart.count');
    Route::get('/items', [CartController::class, 'items'])->name('cart.items');
    Route::post('/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/clear', [CartController::class, 'clear'])->name('cart.clear');
});

/*
|--------------------------------------------------------------------------
| Checkout (public) + Coupons (public)
|--------------------------------------------------------------------------
*/
if (class_exists(CheckoutController::class)) {
    if (method_exists(CheckoutController::class, 'index')) {
        Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    } elseif (method_exists(CheckoutController::class, 'show')) {
        Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
    }

    if (method_exists(CheckoutController::class, 'process')) {
        Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    } elseif (method_exists(CheckoutController::class, 'store')) {
        Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    }
}

// Coupon apply/remove during checkout
if (class_exists(CouponApplyController::class)) {
    Route::prefix('checkout')->group(function () {
        Route::post('/coupon/apply',  [CouponApplyController::class, 'apply'])->name('checkout.coupon.apply');
        Route::post('/coupon/remove', [CouponApplyController::class, 'remove'])->name('checkout.coupon.remove');
    });
}

/*
|--------------------------------------------------------------------------
| Dashboard (auth) - FIXED: Redirects based on user role
|--------------------------------------------------------------------------
*/
Route::get('/dashboard/counts', [DashboardController::class, 'getCounts'])->name('dashboard.counts');

// Main dashboard route - redirects based on user role
Route::get('/dashboard', function () {
    $user = Auth::user();

    // Check the role column directly from your User model
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($user->role === 'hospital_manager') {
        return redirect()->route('hospital.dashboard');
    } else {
        // Default fallback for other authenticated users
        return redirect()->route('home');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Authenticated Appointment Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // Only index here to avoid conflicting with public create/store
    Route::resource('appointments', AppointmentController::class)->only(['index']);

    Route::get('/appointments/export/excel', [AppointmentController::class, 'exportExcel'])->name('appointments.export.excel');
    Route::get('/appointments/export/pdf', [AppointmentController::class, 'exportPdf'])->name('appointments.export.pdf');
    Route::get('appointments/summary', [AppointmentController::class, 'summary'])->name('appointments.summary');
});

/*
|--------------------------------------------------------------------------
| Admin Area (auth + role:admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');
        // Appointments (admin views using same controller)
        Route::get('appointments/{appointment}/available-slots', [AppointmentController::class, 'availableSlots'])
            ->name('appointments.availableSlots');
        Route::get('appointments', [AppointmentController::class, 'index'])->name('appointments.index');
        Route::get('appointments/hospital-qr', [AppointmentController::class, 'indexHospitalQr'])->name('appointments.hospital_qr');
        Route::patch('appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('appointments.updateStatus');
        Route::get('appointments/{appointment}/reschedule', [AppointmentController::class, 'editReschedule'])->name('appointments.reschedule');
        Route::patch('appointments/{appointment}/reschedule', [AppointmentController::class, 'updateReschedule'])->name('appointments.reschedule.update');
        Route::get('appointments/export/excel', [AppointmentController::class, 'exportExcel'])->name('appointments.export.excel');
        Route::get('appointments/export/pdf', [AppointmentController::class, 'exportPdf'])->name('appointments.export.pdf');

        Route::get('appointments/create', [AppointmentController::class, 'adminCreate'])
                ->name('appointments.create');

            Route::post('appointments', [AppointmentController::class, 'adminStore'])
                ->name('appointments.store');

            Route::get(
                'appointments/{appointment}/edit',
                [AppointmentController::class, 'edit']
            )->name('appointments.edit');

            Route::patch(
                'appointments/{appointment}',
                [AppointmentController::class, 'update']
            )->name('appointments.update');
            
            Route::get('appointments/{appointment}', [AppointmentController::class, 'show'])->name('appointments.show');

        // Packages
        Route::resource('packages', AdminPackageController::class);
        Route::put('packages/{package}/status', [AdminPackageController::class, 'toggleStatus'])->name('packages.status');

        // Lab Tests
        Route::resource('labtests', LabTestController::class);

        // Banners
        Route::resource('banners', AdminBannerController::class);
        Route::patch('banners/{banner}/toggle-status', [AdminBannerController::class, 'toggleStatus'])->name('banners.toggle-status');

        // Inquiries
        Route::get('inquiries', [AdminInquiryController::class, 'index'])->name('inquiries.index');
        Route::get('inquiries/{inquiry}', [AdminInquiryController::class, 'show'])->name('inquiries.show');
        Route::patch('inquiries/{inquiry}/status', [AdminInquiryController::class, 'updateStatus'])->name('inquiries.status');
        Route::delete('inquiries/{inquiry}', [AdminInquiryController::class, 'destroy'])->name('inquiries.destroy');

        // NEW: export routes for Contact Us
        Route::get('inquiries/export/pdf', [AdminInquiryController::class, 'exportPdf'])->name('inquiries.export.pdf');
        Route::get('inquiries/export/excel', [AdminInquiryController::class, 'exportExcel'])->name('inquiries.export.excel');

        // Reports
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::post('reports', [ReportController::class, 'store'])->name('reports.store');
        Route::get('reports/{report}/download', [ReportController::class, 'download'])->name('reports.download');
        Route::get('reports/{report}/view', [ReportController::class, 'view'])->name('reports.view');
        Route::post('reports/{report}/share-email', [ReportController::class, 'shareEmail'])->name('reports.share-email');
        Route::delete('reports/{report}', [ReportController::class, 'destroy'])->name('reports.destroy');
        Route::get('reports/export', [ReportController::class, 'export'])->name('reports.export');

        // Blog Management
        Route::resource('blogs', AdminBlogController::class);
        Route::resource('blog-categories', \App\Http\Controllers\Admin\BlogCategoryController::class);

        // Custom Dynamic Pages Management
        Route::resource('pages', \App\Http\Controllers\Admin\PageController::class);

        // Testimonials Management
        Route::resource('testimonials', \App\Http\Controllers\Admin\TestimonialController::class);

        // Hospitals
        Route::get('hospitals', [HospitalController::class, 'index'])->name('hospitals.index');
        Route::get('hospitals/create', [HospitalController::class, 'create'])->name('hospitals.create');
        Route::post('hospitals', [HospitalController::class, 'store'])->name('hospitals.store');
        Route::get('hospitals/{hospital}', [HospitalController::class, 'show'])->name('hospitals.show');
        Route::get('hospitals/{hospital}/edit', [HospitalController::class, 'edit'])->name('hospitals.edit');
        Route::put('hospitals/{hospital}', [HospitalController::class, 'update'])->name('hospitals.update');
        Route::delete('hospitals/{hospital}', [HospitalController::class, 'destroy'])->name('hospitals.destroy');
        Route::post('hospitals/{hospital}/send-reset-link', [HospitalController::class, 'sendResetLink'])->name('hospitals.sendResetLink');
        Route::post('hospitals/{hospital}/send-delete-otp', [HospitalController::class, 'sendDeleteOtp'])->name('hospitals.sendDeleteOtp');
        Route::post('hospitals/{hospital}/verify-delete-otp', [HospitalController::class, 'verifyDeleteOtp'])->name('hospitals.verifyDeleteOtp');

        // Profile Admin
        Route::get('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'show'])
            ->name('profile.show');
        Route::put('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'update'])
            ->name('profile.update');
        Route::put('/profile/password', [\App\Http\Controllers\Admin\ProfileController::class, 'updatePassword'])
            ->name('profile.updatePassword');
        Route::post('/profile/reset-password', [\App\Http\Controllers\Admin\ProfileController::class, 'sendResetLink'])
            ->name('profile.sendResetLink');

        // Coupons (Admin CRUD)
        if (class_exists(AdminCouponController::class)) {
            Route::resource('coupons', AdminCouponController::class)->except(['show']);
        }

        // Dashboard counts
        Route::get('/dashboard/counts', [DashboardController::class, 'getCounts'])
            ->name('dashboard.counts');

        // FAQ Management
        Route::get('/faqs', [FaqAdminController::class, 'index'])->name('faqs.index');
        Route::get('/faqs/create', [FaqAdminController::class, 'create'])->name('faqs.create');
        Route::post('/faqs', [FaqAdminController::class, 'store'])->name('faqs.store');
        Route::get('/faqs/{faq}/edit', [FaqAdminController::class, 'edit'])->name('faqs.edit');
        Route::put('/faqs/{faq}', [FaqAdminController::class, 'update'])->name('faqs.update');
        Route::delete('/faqs/{faq}', [FaqAdminController::class, 'destroy'])->name('faqs.destroy');

        Route::post('/faqs/{faq}/toggle', [FaqAdminController::class, 'toggle'])->name('faqs.toggle');
        Route::patch('/faqs/{faq}/toggle', [FaqAdminController::class, 'toggle'])->name('faqs.toggle');

        // Reminders
        Route::get('/send-reminders', [ReminderController::class, 'send'])->name('send.reminders');
    });

/*
|--------------------------------------------------------------------------
| Hospital Owner Area (auth + role:hospital_manager)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:hospital_manager'])
    ->prefix('hospital')
    ->name('hospital.')
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [HospitalDashboardController::class, 'index'])
            ->name('dashboard');

        // Appointments
        Route::get('/appointments', [HospitalAppointmentController::class, 'index'])
            ->name('appointments.index');
        Route::get('/appointments/{appointment}', [HospitalAppointmentController::class, 'show'])
            ->name('appointments.show');
        Route::get('/appointments/export/pdf', [HospitalAppointmentController::class, 'exportPdf'])
            ->name('appointments.export.pdf');
        Route::get('/appointments/export/excel', [HospitalAppointmentController::class, 'exportExcel'])
            ->name('appointments.export.excel');

        // 🔹 Profile Routes - COMPLETE SET
        Route::get('/profile', [HospitalProfileController::class, 'show'])
            ->name('profile.show');
        Route::put('/profile', [HospitalProfileController::class, 'update'])
            ->name('profile.update');
        Route::put('/profile/password', [HospitalProfileController::class, 'updatePassword'])
            ->name('profile.updatePassword');
        Route::post('/profile/reset-password', [HospitalProfileController::class, 'sendResetLink'])
            ->name('profile.sendResetLink');
    });
/*
|--------------------------------------------------------------------------
| Notifications
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('notifications')->group(function () {
    Route::get('/list', [NotificationsController::class, 'list'])->name('notifications.list');
    Route::post('/mark-read', [NotificationsController::class, 'markRead'])->name('notifications.markRead');
    Route::post('/clear-all', [NotificationsController::class, 'clearAll'])->name('notifications.clearAll');
    Route::get('/', [NotificationsController::class, 'index'])->name('notifications.index');
});

/*
|--------------------------------------------------------------------------
| Search (optional)
|--------------------------------------------------------------------------
*/
if (class_exists(SearchController::class)) {
    Route::get('/search', [SearchController::class, 'index'])->name('search.index');
}
// AJAX search endpoint
Route::get('/ajax-search', [App\Http\Controllers\SearchController::class, 'ajax'])->name('global.search.ajax');
// Global search page (kept as single registration)
Route::get('/search', [App\Http\Controllers\SearchController::class, 'index'])->name('global.search');

// Custom Dynamic Pages
Route::get('/services', [\App\Http\Controllers\PageController::class, 'index'])->name('pages.index');
Route::get('/service/{page:slug}', [\App\Http\Controllers\PageController::class, 'show'])->name('pages.show');

require __DIR__ . '/auth.php';
