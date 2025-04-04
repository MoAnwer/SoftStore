<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Product Details')]
class ProductDetailsPage extends Component
{
    public string $slug;
    
    public function mount($slug)
    {
        return $this->slug = $slug;
    }
    
    public function render()
    {
        return view('livewire.product-details-page', [
            'product'   => Product::query()->where('slug', $this->slug)->firstOrFail()
        ]);
    }
}
