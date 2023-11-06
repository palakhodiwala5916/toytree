<?php

namespace App\DependencyInjection\Framework;

use Symfony\Component\Serializer\SerializerInterface;

trait SerializerDI
{
    protected SerializerInterface $serializer;

    #[\Symfony\Contracts\Service\Attribute\Required]
    public function setSerializer(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @template T
     * @psalm-param T $data
     * @param T $data
     */
    public function serializeToJson(mixed $data, array $groups = []): string
    {
        return $this->serializer->serialize($data, 'json', ['groups' => $groups]);
    }

    /**
     * @template T
     * @psalm-param T $type
     * @return T
     */
    public function deserializeJsonToType(string $json, $type, array $groups = [])
    {
        return $this->serializer->deserialize($json, $type, 'json',  ['groups' => $groups]);
    }

    /**
     * @template T
     * @psalm-param T $type
     * @return T
     */
    public function deserializeArrayToType(array $array, $type, array $groups = [])
    {
        return $this->serializer->deserialize($this->serializeToJson($array), $type, 'json', ['groups' => $groups]);
    }
}
