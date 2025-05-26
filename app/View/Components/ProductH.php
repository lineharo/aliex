<?php
namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use App\Models\Product;

class ProductH extends Component
{
    public $product;

    public function __construct(public int $id)
    {
        $this->product = Product::find($id);
    }

    public function render(): \Illuminate\View\View|string
    {
        return view('components.front.product-h');
    }
}
