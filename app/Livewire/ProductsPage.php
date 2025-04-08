<?php

namespace App\Livewire;

use App\Helpers\CartMangement;
use App\Livewire\Partials\Navbar;
use App\Models\{Brand, Category, Product};
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\{Title, Url};
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Products')]
class ProductsPage extends Component
{
    use WithPagination;
    
    #[Url]
    public $selected_categories = [];

    #[Url]
    public $selected_brands = [];

    #[Url]
    public $featured;

    #[Url]
    public $sale;

    #[Url]
    public $price_range = 5000000;

    #[Url]
    public $sort;

    public $component = 'products-page';

    public function addToCart($productId)
    {
        $totalCount = CartMangement::addItemsToCart($productId);

        $this->dispatch('update-cart-count', totalCount: $totalCount)->to(Navbar::class);

        return LivewireAlert::title('Product added successfuly !')->success()->show();
    }

    public function render()
    {

        $productsQuery = Product::active();

        $productsQuery->when(
            !empty($this->selected_categories), 
            fn($q) => $q->whereIntegerInRaw('category_id', $this->selected_categories)
        )
        ->when(
            !empty($this->selected_brands), 
            fn($q) => $q->whereIntegerInRaw('brand_id', $this->selected_brands)
        )
        ->when(
            $this->featured,
            fn($q) => $q->where('is_featured', 1)
        )
        ->when(
            $this->sale,
            fn($q) => $q->where('on_sale', 1)
        )
        ->when(
            $this->price_range,
            fn($q) => $q->whereBetween('price', [0, $this->price_range])
        )
        ->when(
            $this->sort === 'latest', 
            fn() => $productsQuery->latest()
        )
        ->when(
            $this->sort === 'price', 
            fn() => $productsQuery->orderBy('price', 'desc')
        );
        
        return view('livewire.products-page', [
            'products'      => $productsQuery->paginate(9),
            'brands'        => Brand::active()->get(['id', 'name', 'slug']),
            'categories'    => Category::active()->get(['id', 'name', 'slug'])
        ]);
    }
}
