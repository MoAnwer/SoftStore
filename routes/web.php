<?php

use App\Livewire\Auth\{ForgetPasswordPage, LoginPage, RegisterPage, ResetPasswordPage};
use App\Livewire\SuccessPage;
use App\Livewire\{CancelPage, CartPage, HomePage, CategoriesPage, CheckoutPage, MyOrdersPage, ProductDetailsPage, ProductsPage, MyOrderDetailPage};
use Illuminate\Support\Facades\Route;


Route::get('/', HomePage::class);
Route::get('/products', ProductsPage::class);
Route::get('/categories', CategoriesPage::class);
Route::get('/cart', CartPage::class);
Route::get('/products/{slug}', ProductDetailsPage::class);

Route::middleware('guest')->group(function() {
  Route::get('/login', LoginPage::class)->name('login');
  Route::get('/register', RegisterPage::class);
  Route::get('/forget', ForgetPasswordPage::class)->name('password.request');
  Route::get('/reset/{token}', ResetPasswordPage::class)->name('password.reset');
});


Route::middleware('auth')->group(function() {
  Route::get('logout', function() {
    auth()->logout();
    return redirect('/');
  });
  Route::get('/checkout', CheckoutPage::class);
  Route::get('/my-orders', MyOrdersPage::class);
  Route::get('/my-orders/{order}', MyOrderDetailPage::class);
  Route::get('/success', SuccessPage::class);
  Route::get('/cancel', CancelPage::class);
});