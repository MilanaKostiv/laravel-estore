<?php

namespace App\Services\Product;

use App\Product;
use App\Repositories\ProductRepository;

/**
 * Service for getting product data.
 */
class ProductsService
{
    /**
     * @var ProductPriceFormatter
     */
    private $priceFormatter;

    /**
     * @var ProductRepository 
     */
    private $productRepository;

    /**
     * @param ProductPriceFormatter $productPriceFormatter
     * @param ProductRepository $productRepository
     */
    public function __construct(ProductPriceFormatter $productPriceFormatter, ProductRepository $productRepository)
    {
        $this->priceFormatter = $productPriceFormatter;
        $this->productRepository = $productRepository;
    }

    /**
     * Get product by slug.
     *
     * @param string $slug
     * @return Product
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getBySlug(string $slug): Product
    {
        $product = $this->productRepository->findBySlug($slug);

        return $this->priceFormatter->addFormattedPriceToProduct($product);
    }

    /**
     * Get randomly ordered product collection which doesn't contain given slug.
     *
     * @param string $slug
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    public function getBySlugNotInRandomOrder(string $slug, int $limit): \Illuminate\Support\Collection
    {
        $products  = $this->productRepository->findBySlugNotInRandomOrder($slug, $limit);

        return $this->priceFormatter->addFormattedPriceToProducts($products);
    }

    /**
     * Get randomly ordered featured products.
     *
     * @param $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getFeaturedInRandomOrder(int $limit): \Illuminate\Support\Collection
    {
        $products =  $this->productRepository->findFeaturedInRandomOrder($limit);

        return $this->priceFormatter->addFormattedPriceToProducts(($products));
    }

    /**
     * Get randomly ordered products.
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    public function getInRandomOrder(int $limit = 4): \Illuminate\Support\Collection
    {
        $products = $this->productRepository->findInRandomOrder($limit);

        return $this->priceFormatter->addFormattedPriceToProducts(($products));
    }

    /**
     * Get product by id.
     *
     * @param int $id
     * @return Product
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getById(int $id): Product
    {
        return $this->productRepository->findById($id);
    }

}