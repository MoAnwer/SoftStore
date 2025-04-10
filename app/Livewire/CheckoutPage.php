<?php

namespace App\Livewire;

use Livewire\Attributes\Title;
use Livewire\Component;
use App\Helpers\CartMangement;

#[Title('Checkout')]
class CheckoutPage extends Component
{
    public $firstName;
    public $lastName;
    public $phone;
    public $zipCode;
    public $streetAddress;
    public $city;
    public $state;
    public $paymentMethod;


    public function placeOrder()
    {
        $this->validate([
            'firstName' => 'required',
            'lastName' => 'required',
            'phone' => 'required',
            'zipCode' => 'required',
            'streetAddress' => 'required',
            'city' => 'required',
            'state' => 'required',
            'paymentMethod' => 'required',
        ]);
    }

    public function render()
    {
        $cartItems = CartMangement::getCartItemsFromCookie();
        $grandTotal = CartMangement::calculateGrandTotal($cartItems);

        return view('livewire.checkout-page', [
            'grandTotal' => $grandTotal,
            'cartItems' => $cartItems
        ]);
    }
}
