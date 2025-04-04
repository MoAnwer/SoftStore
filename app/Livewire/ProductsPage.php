<?php

namespace App\Livewire;

use App\Models\{Brand, Category, Product};
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Products')]
class ProductsPage extends Component
{
    use WithPagination;
    
    public function render()
    {
        $productsQuery = Product::active();
        return view('livewire.products-page', [
            'products'      => $productsQuery->paginate(6),
            'brands'        => Brand::active()->get(['id', 'name', 'slug']),
            'categories'    => Category::active()->get(['id', 'name', 'slug'])
        ]);
    }
}
