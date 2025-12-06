<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Shop\AuthController;
use App\Http\Controllers\Shop\ContactController as ShopContactController;
use App\Http\Controllers\Shop\ShopController;
use App\Http\Controllers\Shop\WishlistController; 
use App\Http\Controllers\Shop\ShopDetailsController;
use App\Http\Controllers\Shop\CartController; 
use App\Http\Controllers\Shop\ProductReviewController;
use App\Http\Controllers\Shop\CouponController as ShopCouponController; 
use App\Http\Controllers\Shop\OrderController as ShopOrderController;
use App\Http\Controllers\Shop\HomeController;

use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductImageController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\ProductTagController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductAttributeController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;

/*
|--------------------------------------------------------------------------
| Shop - Trang khách hàng (Công khai)
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

// Auth Khách hàng
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.do');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.do');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout.customer');

// Profile & Đổi mật khẩu
Route::get('/profile', [AuthController::class, 'profile'])->name('customer.profile');
Route::get('/change-password', [AuthController::class, 'showChangePassword'])->name('customer.password');
Route::post('/change-password', [AuthController::class, 'updatePassword'])->name('customer.password.update');

// Cửa hàng & Chi tiết sản phẩm
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/shop-details/{id}', [ShopDetailsController::class, 'show'])->name('shop.details');
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::post('/review', [ProductReviewController::class, 'store'])->name('review.store');

// Yêu thích
Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');
Route::post('/wishlist/toggle/{id}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
Route::post('/wishlist/remove/{id}', [WishlistController::class, 'remove'])->name('wishlist.remove');
Route::post('/wishlist/clear', [WishlistController::class, 'clear'])->name('wishlist.clear');

// Giỏ hàng & Thanh toán
Route::get('/shopping-cart', [CartController::class, 'index'])->name('shopping.cart');
Route::view('/checkout', 'pages.checkout')->name('checkout');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/coupon/apply', [ShopCouponController::class, 'applyCoupon'])->name('coupon.apply');
Route::get('/coupon/remove', [ShopCouponController::class, 'removeCoupon'])->name('coupon.remove');

Route::get('/checkout', [ShopOrderController::class, 'checkout'])->name('shop.checkout');
Route::post('/checkout/place-order', [ShopOrderController::class, 'placeOrder'])->name('shop.place-order');
Route::get('/checkout/momo-return', [\App\Http\Controllers\Shop\OrderController::class, 'momoReturn'])->name('shop.momo.return');
// Về chúng tôi & Liên hệ
Route::view('/about', 'pages.about')->name('about');
Route::view('/contact', 'pages.contact')->name('contact');
Route::post('/contact', [ShopContactController::class, 'store'])->name('contact.send');


/*
|--------------------------------------------------------------------------
| Admin - Trang quản trị
|--------------------------------------------------------------------------
*/

// 1. Route Đăng nhập Admin (KHÔNG DÙNG MIDDLEWARE)
// Để ở ngoài để tránh bị lặp vô tận (redirect loop)
Route::view('/admin', 'admin.login')->name('admin.login');
Route::post('/admin/login', [UserController::class, 'login'])->name('admin.login.do');


// 2. Các Route Admin cần bảo vệ (Sử dụng Middleware 'admin.auth')
Route::middleware(['admin.auth'])->prefix('admin')->name('admin.')->group(function () {

    // Đăng xuất (Phải đăng nhập mới đăng xuất được)
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');

    // Dashboard & doanh thu
    Route::get('/dashboard', [\App\Http\Controllers\Admin\RevenueController::class, 'index'])->name('index');
    Route::get('/revenue', [\App\Http\Controllers\Admin\RevenueController::class, 'index'])->name('revenue.index');

    // Quản lý sản phẩm
    Route::resource('products', ProductController::class)->names([
        'index' => 'products.index',
        'store' => 'products.store',
        'update' => 'products.update',
        'destroy' => 'products.destroy',
    ]);

    // Quản lý hình ảnh, biến thể, thẻ
    Route::prefix('products')->group(function () {
        Route::get('{id}/images', [ProductImageController::class, 'index'])->name('product.images.index');
        Route::post('{id}/images', [ProductImageController::class, 'store'])->name('product.images.store');
        Route::delete('images/{image_id}', [ProductImageController::class, 'destroy'])->name('product.images.destroy');
        Route::post('images/{image_id}/thumbnail', [ProductImageController::class, 'setThumbnail'])->name('product.images.thumbnail');

        Route::get('{id}/variants', [ProductVariantController::class, 'index'])->name('product.variants.index');
        Route::post('{id}/variants', [ProductVariantController::class, 'store'])->name('product.variants.store');
        Route::put('variants/{variant_id}', [ProductVariantController::class, 'update'])->name('product.variants.update');
        Route::delete('variants/{variant_id}', [ProductVariantController::class, 'destroy'])->name('product.variants.destroy');

        Route::get('{id}/tags', [ProductTagController::class, 'index'])->name('product.tags.index');
        Route::post('{id}/tags', [ProductTagController::class, 'update'])->name('product.tags.update');
    });

    // Quản lý người dùng
    Route::get('/customers', [UserController::class, 'customers'])->name('customers.index');
    Route::post('/customers/{user}/toggle-status', [UserController::class, 'toggleCustomerStatus'])->name('customers.toggle');

    // Quản lý danh mục
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'storeCategory'])->name('categories.store');
    Route::put('/categories/{id}', [CategoryController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroyCategory'])->name('categories.destroy');

    // Quản lý thuộc tính sản phẩm: Thương hiệu, Tag, Kích cỡ, Màu sắc
    Route::get('/product-attributes', [ProductAttributeController::class, 'index'])->name('attributes.index');
    Route::post('/brands', [ProductAttributeController::class, 'storeBrand'])->name('brands.store');
    Route::put('/brands/{id}', [ProductAttributeController::class, 'updateBrand'])->name('brands.update');
    Route::delete('/brands/{id}', [ProductAttributeController::class, 'destroyBrand'])->name('brands.destroy');

    Route::post('/tags', [ProductAttributeController::class, 'storeTag'])->name('tags.store');
    Route::put('/tags/{id}', [ProductAttributeController::class, 'updateTag'])->name('tags.update');
    Route::delete('/tags/{id}', [ProductAttributeController::class, 'destroyTag'])->name('tags.destroy');

    Route::post('/sizes', [ProductAttributeController::class, 'storeSize'])->name('sizes.store');
    Route::put('/sizes/{id}', [ProductAttributeController::class, 'updateSize'])->name('sizes.update');
    Route::delete('/sizes/{id}', [ProductAttributeController::class, 'destroySize'])->name('sizes.destroy');

    Route::post('/colors', [ProductAttributeController::class, 'storeColor'])->name('colors.store');
    Route::put('/colors/{id}', [ProductAttributeController::class, 'updateColor'])->name('colors.update');
    Route::delete('/colors/{id}', [ProductAttributeController::class, 'destroyColor'])->name('colors.destroy');

    // Quản lý banner
    Route::resource('banners', BannerController::class)->except(['create', 'edit', 'show']);
    Route::post('banners/{id}/toggle', [BannerController::class, 'toggleStatus'])->name('banners.toggle');

    // Quản lý liên hệ
    Route::resource('contacts', ContactController::class)->except(['create', 'store', 'edit']);

    // Quản lý đơn hàng
    Route::resource('orders', AdminOrderController::class)->except(['create', 'store']);

    // Quản lý nhân viên
    Route::get('/staff', [UserController::class, 'index'])->name('staff.index');
    Route::post('/staff', [UserController::class, 'store'])->name('staff.store');
    Route::put('/staff/{user}', [UserController::class, 'update'])->name('staff.update');
    Route::post('/staff/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('staff.toggle');

    // Quản lý mã giảm giá
    Route::view('/coupons', 'admin.pages.coupons')->name('coupons.index');
    Route::resource('coupons', AdminCouponController::class)->except(['create', 'edit', 'show']);
    Route::post('coupons/{id}/toggle', [AdminCouponController::class, 'toggleStatus'])->name('coupons.toggle');

    // Profile Admin
    Route::get('/profile', function () {
        $uid = session('admin_user_id');
        abort_unless($uid, 403);
        $user = \App\Models\User::find($uid);
        return view('admin.pages.profile', compact('user'));
    })->name('profile');

    Route::get('/change-password', function () {
        $uid = session('admin_user_id');
        abort_unless($uid, 403);
        $user = \App\Models\User::find($uid);
        return view('admin.pages.change-password', compact('user'));
    })->name('change-password');

    Route::post('/change-password', [UserController::class, 'changePassword'])->name('change-password.update');
});