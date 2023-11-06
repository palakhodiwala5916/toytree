<?php

namespace App\Entity\Category;

use App\Entity\AbstractEntity;
use App\Entity\Traits\NameColumn;
use App\Entity\Traits\UpdatedAtColumn;
use App\Repository\Category\CategoryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ORM\Table(name: 'categories')]
class Category extends AbstractEntity
{
    use NameColumn;
    use UpdatedAtColumn;
}
