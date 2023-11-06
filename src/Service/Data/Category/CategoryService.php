<?php

namespace App\Service\Data\Category;

use App\Entity\Category\Category;
use App\Exception\SoftException;
use App\Service\Data\AbstractCRUDService;
use App\VO\Protocol\Api\Category\CategoryBody;
use App\DependencyInjection\Repository\Category\CategoryRepositoryDI;
use App\Repository\Category\CategoryRepository;

final class CategoryService extends AbstractCRUDService
{
    use CategoryRepositoryDI;

    public function getEntityRepository(): CategoryRepository
    {
        return $this->categoryRepository;
    }

    /**
     * @param Category $category
     * @param CategoryBody $body
     * @throws SoftException
     */
    public function updateObjectFields($category, $body): void
    {
        $this->validateObjectFields($category, $body);

        // TODO: Implement updateObjectFields() method.

        $this->saveObject($category);
    }

    /**
     * @param Category $category
     * @param CategoryBody $body
     * @throws SoftException
     */
    public function validateObjectFields($category, $body): void
    {
        // TODO: Implement validateObjectFields() method.
    }

}
