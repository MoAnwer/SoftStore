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
Route::get('/checkout', CheckoutPage::class);
Route::get('/my-orders', MyOrdersPage::class);
Route::get('/my-orders/{order}', MyOrderDetailPage::class);

Route::get('/login', LoginPage::class);
Route::get('/register', RegisterPage::class);
Route::get('/forget', ForgetPasswordPage::class);
Route::get('/reset', ResetPasswordPage::class);

Route::get('/success', SuccessPage::class);
Route::get('/cancel', CancelPage::class);