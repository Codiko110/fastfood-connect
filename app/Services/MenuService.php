<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductExtra;
use App\Events\MenuProductUpdated;
use App\Events\MenuCategoryUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class MenuService
{
    private const CACHE_TTL = 3600;
    private const CACHE_VERSION = 1;

    private function cacheKey(string $key): string
    {
        return "menu_v" . self::CACHE_VERSION . "_" . $key;
    }

    public function getActiveCategories()
    {
        return Cache::remember($this->cacheKey('active_categories'), self::CACHE_TTL, function () {
            return Category::where('is_active', true)->orderBy('sort_order')->get();
        });
    }

    public function getAllCategoriesWithCount()
    {
        return Cache::remember($this->cacheKey('categories_with_count'), self::CACHE_TTL, function () {
            return Category::withCount('products')->orderBy('sort_order')->get();
        });
    }

    public function getAvailableProducts(array $filters = [])
    {
        $query = Product::where('is_available', true);

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['search'])) {
            $query->where('name', 'ilike', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['sort'])) {
            match ($filters['sort']) {
                'price_asc' => $query->orderBy('price'),
                'price_desc' => $query->orderBy('price', 'desc'),
                'name_asc' => $query->orderBy('name'),
                'name_desc' => $query->orderBy('name', 'desc'),
                default => $query->orderBy('sort_order'),
            };
        } else {
            $query->orderBy('sort_order');
        }

        return $query->paginate(12);
    }

    public function getAdminProducts(array $filters = [])
    {
        $query = Product::with('category')->orderBy('sort_order');

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['search'])) {
            $query->where('name', 'ilike', '%' . $filters['search'] . '%');
        }

        return $query->paginate(12);
    }

    public function getRecommendedProducts(Product $product, int $limit = 4)
    {
        return Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_available', true)
            ->take($limit)
            ->get();
    }

    public function createProduct(array $data, ?array $image = null): Product
    {
        $data['is_available'] = $data['is_available'] ?? true;
        $data['price'] = $data['price'] / 5000;

        if ($image) {
            $data['image'] = $image['file']->store('products', 'public');
        }

        $product = Product::create($data);
        Cache::forget($this->cacheKey('categories_with_count'));
        broadcastSafe(new MenuProductUpdated($product, 'created'));

        return $product;
    }

    public function updateProduct(Product $product, array $data, ?array $image = null): void
    {
        $data['is_available'] = $data['is_available'] ?? $product->is_available;
        $data['price'] = $data['price'] / 5000;

        if ($image) {
            $data['image'] = $image['file']->store('products', 'public');
        }

        $product->update($data);
        Cache::forget($this->cacheKey('categories_with_count'));
        broadcastSafe(new MenuProductUpdated($product, 'updated'));
    }

    public function deleteProduct(Product $product): void
    {
        broadcastSafe(new MenuProductUpdated($product, 'deleted'));
        Cache::forget($this->cacheKey('categories_with_count'));
        $product->delete();
    }

    public function toggleAvailability(Product $product): void
    {
        $product->update(['is_available' => !$product->is_available]);
        broadcastSafe(new MenuProductUpdated($product, 'toggled'));
    }

    public function createCategory(array $data): Category
    {
        $data['slug'] = Str::slug($data['name']);
        $category = Category::create($data);
        $this->clearCategoryCache();
        broadcastSafe(new MenuCategoryUpdated($category, 'created'));

        return $category;
    }

    public function updateCategory(Category $category, array $data): void
    {
        $data['slug'] = Str::slug($data['name']);
        $category->update($data);
        $this->clearCategoryCache();
        broadcastSafe(new MenuCategoryUpdated($category, 'updated'));
    }

    public function deleteCategory(Category $category): bool
    {
        if ($category->products()->count() > 0) {
            return false;
        }

        broadcastSafe(new MenuCategoryUpdated($category, 'deleted'));
        $this->clearCategoryCache();
        $category->delete();

        return true;
    }

    private function clearCategoryCache(): void
    {
        Cache::forget($this->cacheKey('active_categories'));
        Cache::forget($this->cacheKey('categories_with_count'));
    }
}
