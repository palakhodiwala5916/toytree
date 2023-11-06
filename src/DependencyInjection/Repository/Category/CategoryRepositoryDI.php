<?php

namespace App\DependencyInjection\Repository\Category;

use App\Repository\Category\CategoryRepository;

trait CategoryRepositoryDI
{
    protected CategoryRepository $categoryRepository;

    /**
     * @required
     */
    public function setCategoryRepository(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }
}
