<?php

namespace App\Controller\Api\V1\Category;

use OpenApi\Attributes\Tag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\DependencyInjection\Service\Data\Category\CategoryServiceDI;
use FOS\RestBundle\Controller\Annotations as Rest;

///**
// * @Rest\Route("/api/v1/category", name="api_v1_category_")
// */
#[Tag(name: 'Toy')]
#[Rest\Route("/api")]
final class CategoryClientController extends AbstractController
{
    use CategoryServiceDI;

    // TODO: Add new actions here

//    /**
//     * @Rest\Post("/{id}/action-name", name="action_name")
//     * @ParamConverter("id")
//     * @ParamConverter("query", converter="app.param_converter.request_query")
//     * @ParamConverter("body", converter="fos_rest.request_body")
//     * @OnlyOwner()
//     * @Rest\View(serializerGroups={"api"})
//     * @throws ApiException
//     */
//    public function actionName(Request $request, MyEntity $object, EntityActionQueryParams $query, EntityActionRequestBody $body): ApiResponseBody
//    {
//        // do something here
//        $data = null;
//
//        return new ApiResponseBody(true, $data);
//    }

}
