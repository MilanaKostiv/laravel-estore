<?php

namespace App\Services\Product;

use App\Product;
use App\Repositories\ProductRepository;
use App\Services\SearchCriteria;

/**
 * Service for getting product data.
 */
class ProductsService
{
    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var SearchCriteria
     */
    private $searchCriteria;

    /**
     * @param ProductRepository $productRepository
     * @param SearchCriteria $searchCriteria
     */
    public function __construct(
        ProductRepository $productRepository,
        SearchCriteria $searchCriteria
    ) {
        $this->productRepository = $productRepository;
        $this->searchCriteria = $searchCriteria;
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
        return $this->productRepository->findBySlug($slug);
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

    /**
     * Get recommended products collection.
     *
     * @param string $slug
     * @param int $recommendedPerPage
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRecommendedList(?string $slug, int $recommendedPerPage): \Illuminate\Database\Eloquent\Collection
    {
        $searchCriteria = $this->searchCriteria;
        $searchCriteria->reset();

        if ($slug !== null) {
            $searchCriteria->addWhere('slug', '<>', $slug);
        }
        $searchCriteria->addSortOrder('rand');
        $searchCriteria->addLimit($recommendedPerPage);

        return $this->productRepository->getList($searchCriteria);
    }

    /**
     * Get product collection.
     *
     * @param string|null $category
     * @param string|null $sortOrder
     * @param int|null $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getList(?string $sortOrder, ?string $category = null, ?int $limit = null)
    {
        $searchCriteria = $this->searchCriteria;
        $searchCriteria->reset();

        $searchCriteria = $this->setSearchCriteriaOrder($searchCriteria, $sortOrder);
        $searchCriteria->addLimit($limit);

        if ($category !== null) {
            $searchCriteria->addRelationship('categories');
            $searchCriteria->addWhere('categories.slug', '=', $category);
        }

        return $this->productRepository->getList($searchCriteria);
    }

    /**
     * Get featured product collection.
     *
     * @param string|null $sortOrder
     * @param int|null $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getFeaturedList(
        ?string $sortOrder = null,
        ?int $limit = null
    ): \Illuminate\Database\Eloquent\Collection {
        $searchCriteria = $this->searchCriteria;
        $searchCriteria->reset();

        $searchCriteria = $this->setSearchCriteriaOrder($searchCriteria, $sortOrder);
        $searchCriteria->addLimit($limit);

        $searchCriteria->addWhere('featured', '=', true);

        return $this->productRepository->getList($searchCriteria);
    }

    /**
     * Set order to Search Criteria.
     *
     * @param string|null $sortOrder
     * @param SearchCriteria $searchCriteria
     * @return SearchCriteria
     */
    private function setSearchCriteriaOrder(SearchCriteria $searchCriteria, ?string $sortOrder): SearchCriteria
    {
        $sortOrdersMap = [
            'low_high' => ['asc', ['price']],
            'high_low' => ['desc', ['price']],
            'rand' => ['rand'],
        ];

        if (isset($sortOrdersMap[$sortOrder])) {
            $searchCriteria->addSortOrder(...$sortOrdersMap[$sortOrder]);
        }

        return $searchCriteria;
    }
}
