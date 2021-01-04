<?php

namespace App\Services;

use App\Repositories\CategoryRepository;
use App\Category;
use Illuminate\Database\Eloquent\Collection;

/**
 * Categories processing.
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
     * Gets all categories.
     *
     * @return Collection
     */
    public function getAllCategories(): Collection
    {
        return $this->categoryRepository->findAllCategories();
    }

    /**
     * Gets category by slug.
     *
     * @param string $slug
     * @return Category|null
     */
    public function getCategoryBySlug(string $slug): ?Category
    {
        return $this->categoryRepository->findCategoryBySlug($slug);
    }
}
