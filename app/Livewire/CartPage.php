<?php

namespace App\Livewire;

use App\Helpers\CartMangement;
use App\Livewire\Partials\Navbar;
use Livewire\Attributes\Title;
use Livewire\Component;


#[Title('Cart')]
class CartPage extends Component
{
    public $cartItems = [];
    public $grandTotal;
    
    public function mount()
    {
        $this->cartItems    = CartMangement::getCartItemsFromCookie();
        $this->grandTotal   = CartMangement::calculateGrandTotal($this->cartItems);
    }

    public function increaseQty($productId)
    {
        $this->cartItems    = CartMangement::incrementQuantityToCartItems($productId);
        $this->grandTotal   = CartMangement::calculateGrandTotal($this->cartItems);
        $this->dispatch('update-cart-count', totalCount: count($this->cartItems))->to(Navbar::class);
    }

    public function decreaseQty($productId)
    {
        $this->cartItems    = CartMangement::decrementQuantityToCartItems($productId);
        $this->grandTotal   = CartMangement::calculateGrandTotal($this->cartItems);
        $this->dispatch('update-cart-count', totalCount: count($this->cartItems))->to(Navbar::class);
    }


    public function render()
    {
        return view('livewire.cart-page');
    }
}
