<?php

namespace App\Repositories;

use App\Product;
use App\Services\SearchCriteria;
use App\Services\EntityProcessor;

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
     * Get product collection by given Search Criteria.
     *
     * @param SearchCriteria $searchCriteria
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getList(SearchCriteria $searchCriteria): \Illuminate\Database\Eloquent\Collection
    {
        $productsProcessor = new EntityProcessor(Product::class);

        return $productsProcessor->process($searchCriteria)->get();
    }
}
