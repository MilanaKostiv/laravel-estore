<?php

namespace App\Services\Category;

use App\Repositories\CategoryRepository;
use App\Category;

/**
 * Service for getting category data.
 */
class CategoriesService
{
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Get all categories.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllCategories(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->categoryRepository->findAllCategories();
    }

    /**
     * Get category by slug.
     *
     * @param string $slug
     * @return Category|null
     */
    public function getCategoryBySlug(string $slug): ?Category
    {
        return $this->categoryRepository->findCategoryBySlug($slug);
    }
}
