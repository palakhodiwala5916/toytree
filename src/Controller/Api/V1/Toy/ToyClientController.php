<?php

namespace App\Controller\Api\V1\Toy;

use App\Entity\Toy;
use App\VO\Protocol\BaseFilters;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes\Tag;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use OpenApi\Attributes as OA;
use App\VO\Protocol\PaginationData;
use App\DependencyInjection\Service\Data\Toy\ToyServiceDI;
use Nelmio\ApiDocBundle\Annotation\Security;
use App\VO\Protocol\Api\Toy\ToyFilters;

#[Tag(name: 'Toy')]
#[Rest\Route("/api")]
class ToyClientController extends AbstractController
{
    use ToyServiceDI;

    // <editor-fold desc="GET /api/v1/toy/list">
    #[Rest\Get('/v1/toy/list', name: 'api_v1_toy_list')]
    #[Rest\View(serializerGroups: ['api'])]
    #[ParamConverter('filters', converter: 'app.param_converter.request_query')]
    #[OA\Response(
        response: 200,
        description: 'List Toys information',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'success', type: 'boolean'),
                new OA\Property(property: 'data', ref: new Model(type: Toy::class))
            ],
            type: 'object'
        )
    )]
    #[OA\Parameter(
        name: 'search',
        description: 'Toy Search',
        in: 'query',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'offset',
        description: 'Offset',
        in: 'query',
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Parameter(
        name: 'limit',
        description: 'Limit',
        in: 'query',
        schema: new OA\Schema(type: 'integer')
    )]
    #[Security(name: 'Bearer')]
    public function listBillingInfo(Request $request,ToyFilters $filters): PaginationData
    {xxx
        $sortBy[0] = (object) [
            'key' => 'createdAt',
            'value' => 'DESC'
        ];
        $filters->sortBy = $sortBy;
        return $this->toyService->filterObjects($filters);
    }
    // </editor-fold>
}