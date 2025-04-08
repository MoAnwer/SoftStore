<?php

namespace App\Livewire;

use App\Helpers\CartMangement;
use App\Livewire\Partials\Navbar;
use App\Models\Product;
use Jantinnerezo\LivewireAlert\Concerns\SweetAlert2;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Product Details')]
class ProductDetailsPage extends Component
{

    use SweetAlert2;

    public string $slug;
    public int $quantity = 1;

    public function mount($slug)
    {
        return $this->slug = $slug;
    }

    public function addToCart($productId)
    {
        $totalCount = CartMangement::addItemsToCartWithQty($productId, $this->quantity);

        $this->dispatch('update-cart-count', totalCount: $totalCount)->to(Navbar::class);

        $this->alert('suucess');

    }

    public function increaseQty() {
        $this->quantity++;
    }

    public function decreaseQty() {
        $this->quantity <= 1 ?: $this->quantity--;
    }
    
    public function render()
    {
        return view('livewire.product-details-page', [
            'product'   => Product::query()->where('slug', $this->slug)->firstOrFail()
        ]);
    }
}
