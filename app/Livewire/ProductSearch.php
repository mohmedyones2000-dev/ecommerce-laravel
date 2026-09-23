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

    // ✅ فلاتر البحث
    public string $search = '';
    public ?int $category = null;
    public ?int $brand = null;
    public ?string $gender = null;
    public ?string $stock_status = null;
    public bool $has_discount = false;
    public ?float $min_price = null;
    public ?float $max_price = null;
    public string $sort = 'latest';

    // ✅ لتحديث URL عند تغيير الفلاتر
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

    /**
     * ✅ إعادة تعيين pagination عند تغيير أي فلتر
     */
    public function updating($property): void
    {
        // إذا تغيّر أي فلتر — نعود للصفحة الأولى
        if (in_array($property, [
            'search', 'category', 'brand', 'gender',
            'stock_status', 'has_discount', 'min_price', 'max_price', 'sort'
        ])) {
            $this->resetPage();
        }
    }

    /**
     * ✅ إعادة تعيين كل الفلاتر
     */
    public function resetFilters(): void
    {
        $this->reset([
            'search', 'category', 'brand', 'gender',
            'stock_status', 'has_discount', 'min_price', 'max_price'
        ]);
        $this->sort = 'latest';
        $this->resetPage();
    }

    /**
     * ✅ عرض المكون
     */
    public function render()
    {
        $products = Product::query()
            ->with([ 'variants', 'category', 'brand'])
            ->where('is_active', true)
            ->search($this->search)
            ->category($this->category)
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
}