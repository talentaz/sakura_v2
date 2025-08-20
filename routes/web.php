<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

//frontend
Route::get('/', [App\Http\Controllers\Frontend\FrontController::class, 'index'])->name('front.home');
Route::get('/light_gallery', [App\Http\Controllers\Frontend\FrontController::class, 'light_gallery'])->name('front.light_gallery');
Route::any('/stock', [App\Http\Controllers\Frontend\StockController::class, 'index'])->name('front.stock');
Route::get('/select_port', [App\Http\Controllers\Frontend\StockController::class, 'select_port'])->name('front.select_port');
Route::get('/details/{id}', [App\Http\Controllers\Frontend\StockController::class, 'details'])->name('front.details');
Route::get('/details/image_download/{id}', [App\Http\Controllers\Frontend\StockController::class, 'image_download'])->name('front.details.image_download');
Route::get('/contact_us', [App\Http\Controllers\Frontend\ContactController::class, 'index'])->name('front.contact');
Route::post('/contact_us/email', [App\Http\Controllers\Frontend\ContactController::class, 'contactEmail'])->name('front.contact.email');
Route::post('/inquery/email', [App\Http\Controllers\Frontend\ContactController::class, 'inquiryEmail'])->name('front.inquiry.email');
Route::get('/company', [App\Http\Controllers\Frontend\ContactController::class, 'company'])->name('front.company');
Route::get('/agents', [App\Http\Controllers\Frontend\ContactController::class, 'agents'])->name('front.agents');
Route::group(['prefix' => 'agents'], function(){
    Route::get('/', [App\Http\Controllers\Frontend\ContactController::class, 'agents'])->name('front.agents');
    Route::get('/tanzania', [App\Http\Controllers\Frontend\ContactController::class, 'tanzania'])->name('front.agents.tanzania');
});
Route::get('/gallery/image', [App\Http\Controllers\Frontend\ContactController::class, 'gallery'])->name('front.gallery');
Route::get('/gallery/video', [App\Http\Controllers\Frontend\GalleryController::class, 'index'])->name('front.video.gallery');
Route::get('/gallery/video/fetch_data', [App\Http\Controllers\Frontend\GalleryController::class, 'fetch_data'])->name('front.video.fetch_data');
Route::get('/payment', [App\Http\Controllers\Frontend\ContactController::class, 'payment'])->name('front.payment');
Route::get('/blog', [App\Http\Controllers\Frontend\BlogController::class, 'index'])->name('front.blog');
Route::get('/blog/{id}', [App\Http\Controllers\Frontend\BlogController::class, 'details'])->name('front.blog.detail');
Route::get('/customer_vocie', [App\Http\Controllers\Frontend\CustomerController::class, 'index'])->name('front.customer_vocie');

// Customer Authentication Routes
Route::group(['prefix' => 'customer'], function(){
    Route::get('/login', [App\Http\Controllers\Frontend\CustomerAuthController::class, 'showLoginForm'])->name('front.customer.login');
    Route::post('/login', [App\Http\Controllers\Frontend\CustomerAuthController::class, 'login'])->name('front.customer.login.post');
    Route::get('/signup', [App\Http\Controllers\Frontend\CustomerAuthController::class, 'showRegistrationForm'])->name('front.customer.signup');
    Route::post('/signup', [App\Http\Controllers\Frontend\CustomerAuthController::class, 'register'])->name('front.customer.signup.post');
    Route::get('/forgot-password', [App\Http\Controllers\Frontend\CustomerAuthController::class, 'showForgotPasswordForm'])->name('front.customer.forgot-password');
    Route::post('/forgot-password', [App\Http\Controllers\Frontend\CustomerAuthController::class, 'sendResetLinkEmail'])->name('front.customer.forgot-password.post');
    Route::get('/reset-password/{token}', [App\Http\Controllers\Frontend\CustomerAuthController::class, 'showResetPasswordForm'])->name('front.customer.reset-password');
    Route::post('/reset-password', [App\Http\Controllers\Frontend\CustomerAuthController::class, 'resetPassword'])->name('front.customer.reset-password.post');
});

// Customer Protected Routes
Route::prefix('/customer')->middleware(['auth:customer'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Frontend\CustomerAuthController::class, 'dashboard'])->name('front.customer.dashboard');
    Route::post('/logout', [App\Http\Controllers\Frontend\CustomerAuthController::class, 'logout'])->name('front.customer.logout');

    // New Dashboard Pages
    Route::get('/inquiries', [App\Http\Controllers\Frontend\CustomerDashboardController::class, 'inquiries'])->name('front.customer.inquiries');
    Route::get('/purchases', [App\Http\Controllers\Frontend\CustomerDashboardController::class, 'purchases'])->name('front.customer.purchases');
    Route::get('/profile', [App\Http\Controllers\Frontend\CustomerDashboardController::class, 'profile'])->name('front.customer.profile');
    Route::post('/profile', [App\Http\Controllers\Frontend\CustomerDashboardController::class, 'updateProfile'])->name('front.customer.profile.update');
    Route::get('/billing/{inquiry}', [App\Http\Controllers\Frontend\CustomerDashboardController::class, 'billingHistory'])->name('front.customer.billing');

    // Legacy routes (keeping for backward compatibility)
    Route::get('/mypage', [App\Http\Controllers\Frontend\UserController::class, 'myPage'])->name('front.customer.mypage');
    Route::post('/mypage_post', [App\Http\Controllers\Frontend\UserController::class, 'mypage_post'])->name('front.customer.mypage_post');
    Route::get('/chatroom', [App\Http\Controllers\Frontend\UserController::class, 'chatRoom'])->name('front.customer.chatroom');
    Route::get('/chatroom/{id}', [App\Http\Controllers\Frontend\UserController::class, 'chatDetail'])->name('front.customer.chatdetail');
    Route::get('/changepassword', [App\Http\Controllers\Frontend\UserController::class, 'changePassword'])->name('front.customer.changepassword');
    Route::post('/comments/create', [App\Http\Controllers\Frontend\UserController::class, 'comments'])->name('front.customer.comment_create');

    //generate quitatoin and invoice template
    Route::get('/inquiry/{id}/generate-pdf', [App\Http\Controllers\Frontend\CustomerDashboardController::class, 'generateInquiryPDF'])->name('customer.inquiry.generate-pdf');
    Route::get('/invoice/{id}/generate-pdf', [App\Http\Controllers\Frontend\CustomerDashboardController::class, 'generateInvoicePDF'])->name('customer.invoice.generate-pdf');
});

// Legacy User Routes (keeping for backward compatibility)
Route::group(['prefix' => 'user'], function(){
    Route::get('/login', [App\Http\Controllers\Frontend\UserController::class, 'login'])->name('front.user.login');
    Route::post('/login_post', [App\Http\Controllers\Frontend\UserController::class, 'login_post'])->name('front.user.login_post');
    Route::get('/signup', [App\Http\Controllers\Frontend\UserController::class, 'signup'])->name('front.user.signup');
    Route::post('/signup_post', [App\Http\Controllers\Frontend\UserController::class, 'signup_post'])->name('front.user.signup_post');
});
Route::prefix('/user')->middleware(['auth:web', 'CustomerRole'])->group(function () {
    Route::get('/mypage', [App\Http\Controllers\Frontend\UserController::class, 'myPage'])->name('front.user.mypage');
    Route::post('/mypage_post', [App\Http\Controllers\Frontend\UserController::class, 'mypage_post'])->name('front.user.mypage_post');
    Route::get('/chatroom', [App\Http\Controllers\Frontend\UserController::class, 'chatRoom'])->name('front.user.chatroom');
    Route::get('/chatroom/{id}', [App\Http\Controllers\Frontend\UserController::class, 'chatDetail'])->name('front.user.chatdetail');
    Route::get('/changepassword', [App\Http\Controllers\Frontend\UserController::class, 'changePassword'])->name('front.user.changepassword');
    Route::post('/comments/create', [App\Http\Controllers\Frontend\UserController::class, 'comments'])->name('front.user.comment_create');
});
Route::get('/clear', [App\Http\Controllers\Frontend\FrontController::class, 'clear'])->name('front.clear');

// admin dashboard
Route::prefix('/admin')->middleware(['auth:web', 'role:admin,sales_manager,sales_agent,shipment_officer,purchaser'])->group(function () {
    // Admin only routes
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('root');
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard.index');
        Route::group(['prefix' => 'notification'], function(){
            Route::get('/', [App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('admin.notification');
            Route::get('/mark', [App\Http\Controllers\Admin\NotificationController::class, 'markNotification'])->name('admin.markNotification');
        });

        // User management routes - admin only
        Route::group(['prefix' => 'user'], function(){
        Route::get('/', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.user');
        Route::get('/create', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('admin.user.create');
        Route::post('/create', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('admin.user.store');
        Route::get('/{id}/edit', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('admin.user.edit');
        Route::post('/{id}/edit', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('admin.user.update');
        Route::get('/{id}/change_passowrd', [App\Http\Controllers\Admin\UserController::class, 'change_password'])->name('admin.user.change_password');
        Route::post('/updatePassword', [App\Http\Controllers\Admin\UserController::class, 'updatePassword'])->name('admin.user.updatePassword');
        Route::post('/delete', [App\Http\Controllers\Admin\UserController::class, 'delete'])->name('admin.user.delete');
        });
    });

        Route::group(['prefix' => 'vehicle'], function(){
            Route::get('/', [App\Http\Controllers\Admin\VehicleController::class, 'index'])->name('admin.vehicle.index');
            Route::get('/get_data', [App\Http\Controllers\Admin\VehicleController::class, 'getData'])->name('admin.vehicle.get_data');
            Route::get('/rate', [App\Http\Controllers\Admin\VehicleController::class, 'rate'])->name('admin.vehicle.rate');
            Route::post('/rate_post', [App\Http\Controllers\Admin\VehicleController::class, 'rate_post'])->name('admin.vehicle.rate_post');
            Route::get('/create', [App\Http\Controllers\Admin\VehicleController::class, 'create'])->name('admin.vehicle.create');
            Route::post('/create_post', [App\Http\Controllers\Admin\VehicleController::class, 'create_post'])->name('admin.vehicle.create_post');
            Route::get('/edit/{id}', [App\Http\Controllers\Admin\VehicleController::class, 'edit'])->name('admin.vehicle.edit');
            Route::post('/edit_post/{id}', [App\Http\Controllers\Admin\VehicleController::class, 'edit_post'])->name('admin.vehicle.edit_post');
            Route::post('/imageDelete', [App\Http\Controllers\Admin\VehicleController::class, 'imageDelete'])->name('admin.vehicle.imageDelete');
            Route::post('/image_all_delete', [App\Http\Controllers\Admin\VehicleController::class, 'image_all_delete'])->name('admin.vehicle.image_all_delete');
            Route::post('/imageAdd', [App\Http\Controllers\Admin\VehicleController::class, 'imageAdd'])->name('admin.vehicle.imageAdd');
            Route::get('/delete', [App\Http\Controllers\Admin\VehicleController::class, 'delete'])->name('admin.vehicle.delete');
            Route::get('/get_status', [App\Http\Controllers\Admin\VehicleController::class, 'get_status'])->name('admin.vehicle.get_status');
            Route::get('/change_status', [App\Http\Controllers\Admin\VehicleController::class, 'change_status'])->name('admin.vehicle.change_status');
            // deleted vehicles list
            Route::get('/deleted_list', [App\Http\Controllers\Admin\VehicleController::class, 'deltedList'])->name('admin.vehicle.deletedList');
            Route::get('/get_deleted_list', [App\Http\Controllers\Admin\VehicleController::class, 'getDeltedList'])->name('admin.vehicle.getDeltedList');
            Route::get('/restore', [App\Http\Controllers\Admin\VehicleController::class, 'restore'])->name('admin.vehicle.restore');
            Route::get('/forceDelete', [App\Http\Controllers\Admin\VehicleController::class, 'forceDelete'])->name('admin.vehicle.force_delete');
        });

        Route::group(['prefix' => 'customer'], function(){
            Route::get('/', [App\Http\Controllers\Admin\CustomerController::class, 'index'])->name('admin.customer.index');
            Route::get('/add', [App\Http\Controllers\Admin\CustomerController::class, 'add'])->name('admin.customer.add');
            Route::post('/add_post', [App\Http\Controllers\Admin\CustomerController::class, 'add_post'])->name('admin.customer.add_post');
            Route::get('/edit/{id}', [App\Http\Controllers\Admin\CustomerController::class, 'edit'])->name('admin.customer.edit');
            Route::post('/edit_post', [App\Http\Controllers\Admin\CustomerController::class, 'edit_post'])->name('admin.customer.edit_post');
            Route::get('/delete', [App\Http\Controllers\Admin\CustomerController::class, 'delete'])->name('admin.customer.delete');
            Route::post('/imageAdd', [App\Http\Controllers\Admin\CustomerController::class, 'imageAdd'])->name('admin.customer.imageAdd');
            Route::post('/imageDelete', [App\Http\Controllers\Admin\CustomerController::class, 'imageDelete'])->name('admin.customer.imageDelete');
        });

        // Customer Management Routes (for actual customer accounts)
        Route::group(['prefix' => 'customer-management'], function(){
            Route::get('/', [App\Http\Controllers\Admin\CustomerManagementController::class, 'index'])->name('admin.customer_management.index');
            Route::get('/create', [App\Http\Controllers\Admin\CustomerManagementController::class, 'create'])->name('admin.customer_management.create');
            Route::post('/store', [App\Http\Controllers\Admin\CustomerManagementController::class, 'store'])->name('admin.customer_management.store');
            Route::get('/{id}', [App\Http\Controllers\Admin\CustomerManagementController::class, 'show'])->name('admin.customer_management.show');
            Route::get('/{id}/edit', [App\Http\Controllers\Admin\CustomerManagementController::class, 'edit'])->name('admin.customer_management.edit');
            Route::put('/{id}', [App\Http\Controllers\Admin\CustomerManagementController::class, 'update'])->name('admin.customer_management.update');
            Route::delete('/{id}', [App\Http\Controllers\Admin\CustomerManagementController::class, 'destroy'])->name('admin.customer_management.destroy');
            Route::post('/{id}/restore', [App\Http\Controllers\Admin\CustomerManagementController::class, 'restore'])->name('admin.customer_management.restore');
            Route::delete('/{id}/force', [App\Http\Controllers\Admin\CustomerManagementController::class, 'forceDelete'])->name('admin.customer_management.force_delete');
            Route::post('/change-status', [App\Http\Controllers\Admin\CustomerManagementController::class, 'changeStatus'])->name('admin.customer_management.change_status');
        });
        Route::group(['prefix' => 'port'], function(){
            Route::get('/', [App\Http\Controllers\Admin\PortController::class, 'index'])->name('admin.port.index');
            Route::get('/edit/{id}', [App\Http\Controllers\Admin\PortController::class, 'edit'])->name('admin.port.edit');
            Route::post('/edit_post', [App\Http\Controllers\Admin\PortController::class, 'edit_post'])->name('admin.port.edit_post');
        });
        Route::group(['prefix' => 'news'], function(){
            Route::get('/', [App\Http\Controllers\Admin\NewsController::class, 'index'])->name('admin.news.index');
            Route::get('/add', [App\Http\Controllers\Admin\NewsController::class, 'add'])->name('admin.news.add');
            Route::post('/add_post', [App\Http\Controllers\Admin\NewsController::class, 'add_post'])->name('admin.news.add_post');
            Route::get('/edit/{id}', [App\Http\Controllers\Admin\NewsController::class, 'edit'])->name('admin.news.edit'); 
            Route::post('/edit_post', [App\Http\Controllers\Admin\NewsController::class, 'edit_post'])->name('admin.news.edit_post');
            Route::get('/delete', [App\Http\Controllers\Admin\NewsController::class, 'delete'])->name('admin.news.delete');
            Route::post('/imageAdd', [App\Http\Controllers\Admin\NewsController::class, 'imageAdd'])->name('admin.news.imageAdd');
            Route::post('/imageDelete', [App\Http\Controllers\Admin\NewsController::class, 'imageDelete'])->name('admin.news.imageDelete');
        });
        Route::group(['prefix' => 'video'], function(){
            Route::get('/', [App\Http\Controllers\Admin\VideoController::class, 'index'])->name('admin.video.index');
            Route::get('/add', [App\Http\Controllers\Admin\VideoController::class, 'add'])->name('admin.video.add');
            Route::get('/get_data', [App\Http\Controllers\Admin\VideoController::class, 'getData'])->name('admin.video.get_data');
            Route::post('/add_post', [App\Http\Controllers\Admin\VideoController::class, 'add_post'])->name('admin.video.add_post');
            Route::get('/edit/{id}', [App\Http\Controllers\Admin\VideoController::class, 'edit'])->name('admin.video.edit'); 
            Route::post('/edit_post', [App\Http\Controllers\Admin\VideoController::class, 'edit_post'])->name('admin.video.edit_post');
            Route::get('/delete', [App\Http\Controllers\Admin\VideoController::class, 'delete'])->name('admin.video.delete');
            Route::post('/videoAdd', [App\Http\Controllers\Admin\VideoController::class, 'videoAdd'])->name('admin.video.videoAdd');
            Route::post('/videoDelete', [App\Http\Controllers\Admin\VideoController::class, 'videoDelete'])->name('admin.video.videoDelete');
        });
        Route::group(['prefix' => 'shipping'], function(){
            Route::get('/', [App\Http\Controllers\Admin\ShippingController::class, 'index'])->name('admin.shipping.index');
            Route::get('/chat/{user_id}', [App\Http\Controllers\Admin\ShippingController::class, 'chat'])->name('admin.shipping.chat');
            Route::get('/chat/{user_id}/{vehicle_id}', [App\Http\Controllers\Admin\ShippingController::class, 'stockChat'])->name('admin.shipping.stockChat');
            Route::post('/reply/create', [App\Http\Controllers\Admin\ShippingController::class, 'reply'])->name('admin.shipping.reply');
            Route::get('/delete', [App\Http\Controllers\Admin\ShippingController::class, 'delete'])->name('admin.shipping.delete');
            Route::get('/change_status', [App\Http\Controllers\Admin\ShippingController::class, 'change_status'])->name('admin.shipping.change_status');
        });
        Route::group(['prefix' => 'category'], function(){
            Route::group(['prefix' => 'body_type'], function(){
                Route::get('/', [App\Http\Controllers\Admin\BodyTypeController::class, 'index'])->name('admin.bodyType.index');
                Route::get('/add', [App\Http\Controllers\Admin\BodyTypeController::class, 'add'])->name('admin.bodyType.add');
                Route::post('/add_post', [App\Http\Controllers\Admin\BodyTypeController::class, 'add_post'])->name('admin.bodyType.add_post');
                Route::get('/edit/{id}', [App\Http\Controllers\Admin\BodyTypeController::class, 'edit'])->name('admin.bodyType.edit');
                Route::post('/edit_post', [App\Http\Controllers\Admin\BodyTypeController::class, 'edit_post'])->name('admin.bodyType.edit_post');
                Route::get('/delete', [App\Http\Controllers\Admin\BodyTypeController::class, 'delete'])->name('admin.bodyType.delete');
            });
            Route::group(['prefix' => 'maker_type'], function(){
                Route::get('/', [App\Http\Controllers\Admin\MakerTypeController::class, 'index'])->name('admin.makerType.index');
                Route::get('/add', [App\Http\Controllers\Admin\MakerTypeController::class, 'add'])->name('admin.makerType.add');
                Route::post('/add_post', [App\Http\Controllers\Admin\MakerTypeController::class, 'add_post'])->name('admin.makerType.add_post');
                Route::get('/edit/{id}', [App\Http\Controllers\Admin\MakerTypeController::class, 'edit'])->name('admin.makerType.edit');
                Route::post('/edit_post', [App\Http\Controllers\Admin\MakerTypeController::class, 'edit_post'])->name('admin.makerType.edit_post');
                Route::get('/delete', [App\Http\Controllers\Admin\MakerTypeController::class, 'delete'])->name('admin.makerType.delete');
            });
        });
        Route::group(['prefix' => 'page_setting'], function(){
            Route::get('/', [App\Http\Controllers\Admin\PageSettingController::class, 'index'])->name('admin.page_setting.index');
            Route::get('/create', [App\Http\Controllers\Admin\PageSettingController::class, 'create'])->name('admin.page_setting.create');
            Route::post('/create', [App\Http\Controllers\Admin\PageSettingController::class, 'store'])->name('admin.page_setting.store');
            Route::get('/{id}/edit', [App\Http\Controllers\Admin\PageSettingController::class, 'edit'])->name('admin.page_setting.edit');
            Route::post('/{id}/edit', [App\Http\Controllers\Admin\PageSettingController::class, 'update'])->name('admin.page_setting.update');
            Route::delete('/{id}', [App\Http\Controllers\Admin\PageSettingController::class, 'destroy'])->name('admin.page_setting.destroy');
        });
    });

    // Profile routes accessible by all admin users
    Route::get('/edit_profile', [App\Http\Controllers\Admin\AdminController::class, 'edit_profile'])->name('admin.edit_profile');
    Route::post('/update_profile', [App\Http\Controllers\Admin\AdminController::class, 'update_profile'])->name('admin.update_profile');

    // Reports routes accessible by all admin users
    Route::group(['prefix' => 'reports'], function(){
        Route::group(['prefix' => 'vehicle-sales'], function(){
            Route::get('/', [App\Http\Controllers\Admin\ReportController::class, 'vehicleSalesReport'])->name('admin.reports.vehicle_sales');
            Route::get('/export', [App\Http\Controllers\Admin\ReportController::class, 'exportVehicleSalesReport'])->name('admin.reports.vehicle_sales.export');
        });
        Route::group(['prefix' => 'customer-purchase'], function(){
            Route::get('/', [App\Http\Controllers\Admin\ReportController::class, 'customerPurchaseReport'])->name('admin.reports.customer_purchase');
            Route::get('/export', [App\Http\Controllers\Admin\ReportController::class, 'exportCustomerPurchaseReport'])->name('admin.reports.customer_purchase.export');
        });
        Route::group(['prefix' => 'agent-performance'], function(){
            Route::get('/', [App\Http\Controllers\Admin\ReportController::class, 'agentPerformanceReport'])->name('admin.reports.agent_performance');
            Route::get('/export', [App\Http\Controllers\Admin\ReportController::class, 'exportAgentPerformanceReport'])->name('admin.reports.agent_performance.export');
        });
        Route::get('/get-models-by-maker', [App\Http\Controllers\Admin\ReportController::class, 'getModelsByMaker'])->name('admin.reports.get_models_by_maker');
    });

    // Routes accessible by admin, sales_manager, and sales_agent
    Route::middleware(['role:admin,sales_manager,sales_agent'])->group(function () {
        Route::group(['prefix' => 'inquiry'], function(){
            Route::get('/', [App\Http\Controllers\Admin\InquiryController::class, 'index'])->name('admin.inquiry.index');
            Route::get('/create', [App\Http\Controllers\Admin\InquiryController::class, 'create'])->name('admin.inquiry.create');
            Route::post('/store', [App\Http\Controllers\Admin\InquiryController::class, 'store'])->name('admin.inquiry.store');
            Route::get('/{id}', [App\Http\Controllers\Admin\InquiryController::class, 'show'])->name('admin.inquiry.show');
            Route::get('/{id}/edit', [App\Http\Controllers\Admin\InquiryController::class, 'edit'])->name('admin.inquiry.edit');
            Route::put('/{id}', [App\Http\Controllers\Admin\InquiryController::class, 'update'])->name('admin.inquiry.update');
            Route::delete('/{id}', [App\Http\Controllers\Admin\InquiryController::class, 'destroy'])->name('admin.inquiry.destroy');
            Route::post('/detail', [App\Http\Controllers\Admin\InquiryController::class, 'detail'])->name('admin.inquiry.detail');
            Route::get('/delete', [App\Http\Controllers\Admin\InquiryController::class, 'delete'])->name('admin.inquiry.delete');
            Route::post('/update-status', [App\Http\Controllers\Admin\InquiryController::class, 'updateStatus'])->name('admin.inquiry.updateStatus');
            Route::get('/{id}/generate-pdf', [App\Http\Controllers\Admin\InquiryController::class, 'generatePDF'])->name('admin.inquiry.generatePDF');
            Route::get('/{id}/generate-invoice', [App\Http\Controllers\Admin\InquiryController::class, 'generateInvoice'])->name('admin.inquiry.generateInvoice');
            Route::post('/{id}/create-invoice', [App\Http\Controllers\Admin\InquiryController::class, 'createInvoice'])->name('admin.inquiry.createInvoice');
        });
        Route::group(['prefix' => 'invoice'], function(){
            Route::get('/', [App\Http\Controllers\Admin\InvoiceController::class, 'index'])->name('admin.invoice.index');
            Route::get('/create', [App\Http\Controllers\Admin\InvoiceController::class, 'create'])->name('admin.invoice.create');
            Route::post('/store', [App\Http\Controllers\Admin\InvoiceController::class, 'store'])->name('admin.invoice.store');
            Route::get('/{id}/edit', [App\Http\Controllers\Admin\InvoiceController::class, 'edit'])->name('admin.invoice.edit');
            Route::get('/{id}/generate-pdf', [App\Http\Controllers\Admin\InvoiceController::class, 'generatePDF'])->name('admin.invoice.generatePDF');
            Route::post('/billing/store', [App\Http\Controllers\Admin\InvoiceController::class, 'storeBilling'])->name('admin.invoice.billing.store');
            Route::put('/billing/verify/{invoice_id}', [App\Http\Controllers\Admin\InvoiceController::class, 'verifyBilling'])->name('admin.invoice.billing.verify');
            Route::put('/billing/{id}', [App\Http\Controllers\Admin\InvoiceController::class, 'updateBilling'])->name('admin.invoice.billing.update');
            Route::delete('/billing/{id}', [App\Http\Controllers\Admin\InvoiceController::class, 'deleteBilling'])->name('admin.invoice.billing.delete');
        });
});
Route::group(['prefix' => 'notify'], function(){
    Route::get('/', [App\Http\Controllers\Frontend\NotifyController::class, 'index'])->name('auto.email');
    Route::get('/create', [App\Http\Controllers\Frontend\NotifyController::class, 'create'])->name('notify.create');
});

//Update User Details
Route::post('/update-profile/{id}', [App\Http\Controllers\HomeController::class, 'updateProfile'])->name('updateProfile');
Route::post('/update-password/{id}', [App\Http\Controllers\HomeController::class, 'updatePassword'])->name('updatePassword');

// Route::get('{any}', [App\Http\Controllers\HomeController::class, 'index'])->name('index');

//Language Translation
Route::get('index/{locale}', [App\Http\Controllers\HomeController::class, 'lang']);



