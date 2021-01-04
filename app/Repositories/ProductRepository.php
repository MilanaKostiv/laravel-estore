<?php

namespace App\Repositories;

use App\Product;
use App\Services\SearchCriteria;
use App\Services\EntityProcessor;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

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
     * @throws ModelNotFoundException
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
     * @throws ModelNotFoundException
     */
    public function findById(int $id): Product
    {
        return Product::findOrFail($id);
    }

    /**
     * Find product categories.
     *
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findProductCategories(int $id): \Illuminate\Database\Eloquent\Collection
    {
        $product = $this->findById($id);

        return $product->categories()->get();
    }

    /**
     * Delete product categories.
     *
     * @param int $id
     * @return void
     */
    public function deleteProductCategories(int $id): void
    {
        $product = $this->findById($id);

        $product->categories()->detach();
    }

    /**
     * Add product categories.
     *
     * @param int $id
     * @param array $categories
     * @return void
     */
    public function addProductCategories(int $id, array $categories): void
    {
        $product = $this->findById($id);
        $product->categories()->attach($categories);
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

    /**
     * Update product quantity.
     *
     * @param Collection $items
     * @return void
     */
    public function updateQuantity(Collection $items)
    {
        foreach ($items as $item) {
            $product = Product::find($item->model->id);
            $product->update(['quantity' => $product->quantity - $item->qty]);
        }
    }
}
