<?php

namespace App\Repositories;

use App\Category;

/**
 * Fetch category related data.
 */
class CategoryRepository
{
    /**
     * Find all categories.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findAllCategories(): \Illuminate\Database\Eloquent\Collection
    {
        return Category::all();
    }

    /**
     * Get category by slug.
     *
     * @param string $slug
     * @return Category|null
     */
    public function findCategoryBySlug(string $slug): ?Category
    {
        return Category::where('slug', $slug)->first();
    }
}