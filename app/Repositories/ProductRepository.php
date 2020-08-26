<?php

namespace App\Repositories;

use App\Product;

/**
 * Fetch product related data.
 */
class ProductRepository
{
    /**
     * Find product by slug.
     *
     * @param string $slug
     * @return Product
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findBySlug(string $slug): Product
    {

        return Product::where('slug', $slug)->firstOrFail();
    }

    /**
     * Find randomly ordered featured products.
     *
     * @param int|null $limit
     * @return \Illuminate\Support\Collection
     */
    public function findFeaturedInRandomOrder(int $limit = null): \Illuminate\Support\Collection
    {
        /** @var \Illuminate\Database\Eloquent\Builder $productBuilder */
        $productBuilder = Product::inRandomOrder()->where('featured', true);
        if ($limit) {
            $productBuilder->take($limit);
        }
        return $productBuilder->get();
    }

    /**
     * Find randomly ordered products
     *
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    public function findInRandomOrder(int $limit): \Illuminate\Support\Collection
    {
        return Product::take($limit)->inRandomOrder()->get();
    }

    /**
     * Find randomly ordered product collection which doesn't contain given slug.
     *
     * @param string $slug
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    public function findBySlugNotInRandomOrder(string $slug, int $limit): \Illuminate\Support\Collection
    {
        return Product::inRandomOrder()->take($limit)->where('slug', '!=', $slug)->get();
    }

    /**
     * Find product by id.
     *
     * @param int $id
     * @return Product
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findById(int $id): Product
    {
        return Product::findOrFail($id);
    }

    /**
     * Find products from specified category.
     *
     * @param string $categorySlug
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findProductsInCategory(string $categorySlug): \Illuminate\Database\Eloquent\Collection
    {
         return Product::with('categories')->whereHas('categories', function($query) use ($categorySlug) {
            $query->where('slug', $categorySlug);
        })->get();
    }
}
