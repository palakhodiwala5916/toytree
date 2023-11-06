<?php

namespace App\DependencyInjection\Service\Data\Category;

use App\Service\Data\Category\CategoryService;

trait CategoryServiceDI
{
    protected CategoryService $categoryService;

    /**
     * @required
     */
    public function setCategoryService(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }
}
