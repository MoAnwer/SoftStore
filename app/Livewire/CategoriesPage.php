<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Categories')]
class CategoriesPage extends Component
{
    public function render()
    {
        $categories = Category::active()->get();
        return view('livewire.categories-page', [
            'categories' => $categories
        ]);
    }
}
