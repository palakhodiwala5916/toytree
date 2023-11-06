<?php

namespace App\Service\Data;

use App\DependencyInjection\Framework\EntityManagerDI;
use App\DependencyInjection\Framework\LoggerDI;
use App\Entity\AbstractEntity;
use App\Exception\ApiException;
use App\Repository\ExtendedEntityRepository;
use App\VO\Protocol\BaseFilters;
use App\VO\Protocol\PaginationData;
use App\VO\Protocol\SearchFilters;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

abstract class AbstractCRUDService
{
    use EntityManagerDI;
    use LoggerDI;

    public abstract function getEntityRepository(): ExtendedEntityRepository;

    /**
     * @throws ApiException
     */
    public function filterObjects(BaseFilters $filters): PaginationData
    {
        $data = new PaginationData();
        try {
            $data->items = $this->getEntityRepository()->filterBy($filters);
            $data->totalCount = $this->getEntityRepository()->countBy($filters);
        } catch (NoResultException|NonUniqueResultException $e) {
            $this->logger->critical(sprintf('[AbstractCRUDService::filterObjects] %s', $e->getMessage()));
            $this->logger->critical(sprintf('[AbstractCRUDService::filterObjects] %s', $e->getTraceAsString()));

            throw new ApiException('repository.filter_query.error');
        }

        return $data;
    }

    /**
     * @throws ApiException
     */
    public function search(SearchFilters $filters): array
    {
        try {
            return $this->getEntityRepository()->searchBy($filters);
        } catch (NoResultException|NonUniqueResultException $e) {
            $this->logger->critical(sprintf('[AbstractCRUDService::search] %s', $e->getMessage()));
            $this->logger->critical(sprintf('[AbstractCRUDService::search] %s', $e->getTraceAsString()));

            throw new ApiException('repository.search_query.error');
        }
    }

    public function saveObject(AbstractEntity $object)
    {
        $this->entityManager->persist($object);
        $this->entityManager->flush();
    }

    public function removeObject(AbstractEntity $object)
    {
        $this->entityManager->remove($object);
        $this->entityManager->flush();
    }

    public function getAbsoluteUrl(UrlGeneratorInterface $router): string
    {
        $scheme = $router->getContext()->getScheme();
        $host = $router->getContext()->getHost();
        $port = $router->getContext()->getHttpPort();

        return $port != 80 ? sprintf('%s://%s:%s', $scheme, $host, $port) : sprintf('%s://%s', $scheme, $host);

    }


}
