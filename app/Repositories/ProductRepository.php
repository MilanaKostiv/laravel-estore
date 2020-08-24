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
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    public function findFeaturedInRandomOrder(int $limit): \Illuminate\Support\Collection
    {
        return Product::inRandomOrder()->take($limit)->where('featured', true)->get();
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
}