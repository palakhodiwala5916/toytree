<?php

namespace App\Serializer;

use App\DependencyInjection\Service\Data\Toy\ToyServiceDI;
use App\Entity\Toy;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class ToyNormalizer implements NormalizerInterface
{
    use ToyServiceDI;

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
        if (is_array($data) && $object->getFile()) {
            $data['file'] = $this->toyService->getAbsoluteUrl($this->router).'/uploads/toy_asset/'.$object->getFile();
        }

        return $data;
    }

    public function supportsNormalization($data, string $format = null)
    {
        return $data instanceof Toy;
    }
}