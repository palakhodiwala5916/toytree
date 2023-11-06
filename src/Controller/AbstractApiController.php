<?php

namespace App\Controller;

use App\DependencyInjection\Framework\EntityManagerDI;
use App\DependencyInjection\Framework\LoggerDI;
use App\DependencyInjection\Framework\SerializerDI;
use App\DependencyInjection\Service\SecurityServiceDI;
use App\DependencyInjection\Service\ValidatorServiceDI;
use App\Entity\User\User;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class AbstractApiController
{
    use EntityManagerDI;
    use LoggerDI;
    use ValidatorServiceDI;
    use SerializerDI;
    use SecurityServiceDI;

}
