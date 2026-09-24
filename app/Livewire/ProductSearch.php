<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductSearch extends Component
{
    use WithPagination;

    public string $search = '';
    public ?string $category = null;
    public ?int $brand = null;
    public ?string $gender = null;
    public ?string $stock_status = null;
    public bool $has_discount = false;
    public ?float $min_price = null;
    public ?float $max_price = null;
    public string $sort = 'latest';

    protected $queryString = [
        'search'       => ['except' => ''],
        'category'     => ['except' => null],
        'brand'        => ['except' => null],
        'gender'       => ['except' => null],
        'stock_status' => ['except' => null],
        'has_discount' => ['except' => false],
        'min_price'    => ['except' => null],
        'max_price'    => ['except' => null],
        'sort'         => ['except' => 'latest'],
    ];

    public function updating($property): void
    {
        if (in_array($property, [
            'search', 'category', 'brand', 'gender',
            'stock_status', 'has_discount', 'min_price', 'max_price', 'sort'
        ])) {
            $this->resetPage();
        }
    }

    public function resetFilters(): void
    {
        $this->reset([
            'search', 'category', 'brand', 'gender',
            'stock_status', 'has_discount', 'min_price', 'max_price'
        ]);
        $this->sort = 'latest';
        $this->resetPage();
    }

    public function render()
    {
        $products = Product::query()
            ->with(['variants', 'category', 'brand'])
            ->where('is_active', true)
            ->search($this->search)
            ->category($this->resolveCategoryId())
            ->brand($this->brand)
            ->gender($this->gender)
            ->stockStatus($this->stock_status)
            ->hasDiscount($this->has_discount)
            ->priceRange($this->min_price, $this->max_price)
            ->sort($this->sort)
            ->paginate(12);

        return view('livewire.product-search', [
            'products' => $products,
            'categories' => Category::orderBy('name')->get(),
            'brands' => Brand::orderBy('name')->get(),
        ]);
    }

    protected function resolveCategoryId(): ?int
    {
        if (empty($this->category)) {
            return null;
        }

        if (is_numeric($this->category)) {
            return (int) $this->category;
        }

        return Category::where('slug', $this->category)->value('id');
    }
}