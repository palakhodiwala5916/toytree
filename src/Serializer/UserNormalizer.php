<?php

namespace App\Serializer;

use App\DependencyInjection\Service\Data\User\UserServiceDI;
use App\Entity\User\User;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class UserNormalizer implements NormalizerInterface
{
    use UserServiceDI;

    private ObjectNormalizer $normalizer;
    private UrlGeneratorInterface $router;

    public function __construct(UrlGeneratorInterface $router, ObjectNormalizer $normalizer)
    {
        $this->router = $router;
        $this->normalizer = $normalizer;
    }

    public function normalize($object, string $format = null, array $context = [])
    {
        $data = $this->normalizer->normalize($object, $format, $context);
        // Here, add, edit, or delete some data:
        if (is_array($data) && $object->getProfilePicture()) {
            $data['profilePicture'] = $this->userService->getAbsoluteUrl($this->router) . '/uploads/user/' . $object->getProfilePicture();
        }

        if (is_array($data) && $object->getRole()) {
            $data['role'] = $object->getRole();
        }

        if ($object->getCreatedAt()) {
            $data['joinDate'] = $object->getCreatedAt()->format('M Y');
        }

        return $data;
    }

    public function supportsNormalization($data, string $format = null)
    {
        return $data instanceof User;
    }
}
