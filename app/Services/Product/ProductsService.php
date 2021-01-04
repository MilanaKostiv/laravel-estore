<?php

namespace App\Services\Product;

use App\Product;
use App\Repositories\ProductRepository;
use App\Services\SearchCriteria;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Service to work with product.
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
     * Gets product by slug.
     *
     * @param string $slug
     * @return Product
     *
     * @throws ModelNotFoundException
     */
    public function getBySlug(string $slug): Product
    {
        return $this->productRepository->findBySlug($slug);
    }

    /**
     * Gets product by id.
     *
     * @param int $id
     * @return Product
     *
     * @throws ModelNotFoundException
     */
    public function getById(int $id): Product
    {
        return $this->productRepository->findById($id);
    }

    /**
     * Gets recommended products collection.
     *
     * @param string|null $slug
     * @param int $recommendedPerPage
     * @return Collection
     */
    public function getRecommendedList(?string $slug, int $recommendedPerPage): Collection
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
     * Gets product collection.
     *
     * @param string|null $category
     * @param string|null $sortOrder
     * @param int|null $limit
     * @return Collection
     */
    public function getList(
        ?string $sortOrder,
        ?string $category = null,
        ?int $limit = null
    ) : Collection
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
     * Gets featured product collection.
     *
     * @param string|null $sortOrder
     * @param int|null $limit
     * @return Collection
     */
    public function getFeaturedList(
        ?string $sortOrder = null,
        ?int $limit = null
    ): Collection {
        $searchCriteria = $this->searchCriteria;
        $searchCriteria->reset();

        $searchCriteria = $this->setSearchCriteriaOrder($searchCriteria, $sortOrder);
        $searchCriteria->addLimit($limit);

        $searchCriteria->addWhere('featured', '=', true);

        return $this->productRepository->getList($searchCriteria);
    }

    /**
     * Gets product categories.
     *
     * @param int $id
     * @return Collection
     */
    public function getProductCategories(int $id): Collection
    {
        return $this->productRepository->findProductCategories($id);
    }

    /**
     * Deletes product categories.
     *
     * @param int $id
     * @return void
     */
    public function deleteProductCategories(int $id): void
    {
        $this->productRepository->deleteProductCategories($id);
    }

    /**
     * Adds product categories.
     *
     * @param int $id
     * @param array $categories
     * @return void
     */
    public function addProductCategories(int $id, array $categories): void
    {
        $this->productRepository->addProductCategories($id, $categories);
    }

    /**
     * Sets order to Search Criteria.
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

    /**
     * Updates product quantity.
     *
     * @param \Illuminate\Support\Collection $items
     */
    public function updateQuantity(\Illuminate\Support\Collection $items)
    {
        $this->productRepository->updateQuantity($items);
    }
}
