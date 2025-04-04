<?php

namespace App\Livewire;

use App\Models\{Brand, Category};
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Home')]
class HomePage extends Component
{

    public function render()
    {
        $brands = Brand::where('is_active', 1)->get();
        $categories = Category::active()->get();
        return view('livewire.home-page', ['brands' => $brands, 'categories' => $categories]);
    }
}
