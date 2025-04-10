# SoftStore - E-commerce

SoftStore is [TALL Stack](https://tallstack.dev/) e-commerce project build using :

- [Laravel 12.x](https://laravel.com). 
- [Livewire v3](livewire.laravel.com).
- [FilamentPHP v3](https://filamentphp.com).
- [Alphinejs](https://alpinejs.dev/).
- [Tailwindcss](https://tailwindcss.com).
- [Vite](https://vite.dev).
- [Preline UI](https://preline.co).
- [Livewire Alert](https://github.com/jantinnerezo/livewire-alert)
- Sqlite.
- [Stripe](https://stripe.com/) (in progress)
- Stripe PHP SDK


# Features :

⚡ Powerful & modren admin panle that build with [FilamentPHP](https://filamentphp.com).

⚡ Markdown Editor in create products page

⚡ Dark & Light mode

⚡ Fast navigating using `wire:navigate` to faster page loading

⚡ Filters & realtime search in datatables

⚡ Real-time updates & interactive UI using [Livewire v3](livewire.laravel.com)

⚡ Full Authentication login, registration, forgot password, reset password.

⚡ Payment getway integration with [Stripe](https://stripe.com/)


## Admin Panel 💻👏🏼

<img src="screens/dashboard.png">
<img src="screens/orders_page.jpg">
<img src="screens/products_page.jpg">
<img src="screens/products_page.jpg">
<img src="screens/categories.jpg">
<img src="screens/edit_order.jpg">
<img src="screens/repater.jpg">

---

## Store & User panel

<img src="screens/hero-section.jpg">
<img src="screens/brands-section.jpg">
<img src="screens/categories-section.jpg">
<img src="screens/reviews.jpg">
<img src="screens/products-page.jpg">
<img src="screens/cart-page.jpg">

### Auth Pages
<img src="screens/login.jpg">
<img src="screens/forgot.jpg">
<img src="screens/reset.jpg">



---

## install & run ⚡

1- install laravel farmework dependencies
```
composer install
```

2- install `laravel-vite-plugin`
```
npm install
```
3- install Livewire alert 
```
composer require jantinnerezo/livewire-alert
```
4- install `tailwindcss`
```
npm install tailwindcss -D autoprefixer
&&
npx tailwind init
```

5- install `preline ui` 

```
npm i preline
```

6- run database migrations

```
php artisan migrate
```

7- run application
```
php artisan serve
```
