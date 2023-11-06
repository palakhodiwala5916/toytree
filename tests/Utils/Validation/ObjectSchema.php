<?php

namespace App\Tests\Utils\Validation;

use Doctrine\Common\Collections\ArrayCollection;

class ObjectSchema
{
    /** @var ObjectField[]|ArrayCollection */
    private ArrayCollection $fields;

    private string $classname;
    private bool   $isArray;
    private bool   $nullable;
    private array  $serializationGroups;
    private int    $depth;

    protected function __construct()
    {
        $this->fields = new ArrayCollection();
        $this->serializationGroups = [];
        $this->nullable = false;
    }

    public static function fromSchema(ObjectSchema $view, bool $isArray, bool $nullable = false): ObjectSchema
    {
        $schema = clone $view;
        $schema->isArray = $isArray;
        $schema->nullable = $nullable;

        return $schema;
    }

    public function __toString()
    {
        return sprintf('ObjectSchema(%s)', $this->getFields()->count());
    }

    public static function fromClass(string $classname, array $serializationGroups = [], bool $isArray = false, int $depth = 0): ObjectSchema
    {
        $schema = new static();
        $schema->setClassname($classname);
        $schema->setSerializationGroups(array_merge(['api'], $serializationGroups));
        $schema->setIsArray($isArray);
        $schema->setDepth($depth);

        return $schema;
    }

    public static function fromArray(array $properties, bool $isArray = false, bool $nullable = false): ObjectSchema
    {
        $schema = new static();
        $schema->setIsArray($isArray);
        $schema->setNullable($nullable);
        foreach ($properties as $property => $type) {
            if (is_array($type) && !empty($type[0]) && is_array($type[0])) {
                $schema->addField($property, ObjectSchema::fromArray($type[0], true));
            } elseif (is_array($type)) {
                $schema->addField($property, ObjectSchema::fromArray($type));
            } elseif (is_string($type) || $type instanceof ObjectSchema) {
                $schema->addField($property, $type);
            }
        }

        return $schema;
    }

    public static function create(bool $isArray = false): ObjectSchema
    {
        $schema = new static();
        $schema->setIsArray($isArray);

        return $schema;
    }

    //<editor-fold desc="Accessors">

    /**
     * @return ObjectField[]|ArrayCollection
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param ObjectField[]|ArrayCollection $fields
     */
    public function setFields($fields): void
    {
        $this->fields = $fields;
    }

    /**
     * @return string
     */
    public function getClassname(): string
    {
        return $this->classname;
    }

    /**
     * @param string $classname
     */
    public function setClassname(string $classname): void
    {
        $this->classname = $classname;
    }

    /**
     * @return bool
     */
    public function isArray(): bool
    {
        return $this->isArray;
    }

    /**
     * @param bool $isArray
     */
    public function setIsArray(bool $isArray): void
    {
        $this->isArray = $isArray;
    }

    /**
     * @return string[]
     */
    public function getSerializationGroups(): array
    {
        return $this->serializationGroups;
    }

    /**
     * @param string[] $serializationGroups
     */
    public function setSerializationGroups(array $serializationGroups): void
    {
        $this->serializationGroups = $serializationGroups;
    }

    /**
     * @return int
     */
    public function getDepth(): int
    {
        return $this->depth;
    }

    /**
     * @param int $depth
     */
    public function setDepth(int $depth): void
    {
        $this->depth = $depth;
    }

    /**
     * @return bool
     */
    public function isNullable(): bool
    {
        return $this->nullable;
    }

    /**
     * @param bool $nullable
     */
    public function setNullable(bool $nullable): void
    {
        $this->nullable = $nullable;
    }

    //</editor-fold>

    public function getField(string $name): ?ObjectField
    {
        $filter = $this->getFields()->filter(function (ObjectField $field) use ($name) {
            return $field->name === $name;
        });

        return $filter->first() ? $filter->first() : null;
    }

    public function removeField(string $name): ObjectSchema
    {
        $fields = $this->getFields()->filter(function (ObjectField $field) use ($name) {
            return $field->name !== $name;
        });
        $this->fields = $fields;

        return $this;
    }

    public function addField(string $name, $type, bool $isRequired = true): ObjectSchema
    {
        $field = new ObjectField($name, $type, $isRequired);
        $this->getFields()->add($field);

        return $this;
    }

    private function getPropertyType(string $type, bool $isArray)
    {
        if ($this->dataTypeIsClassname($type)) {
            return ObjectSchema::fromClass($type, $this->serializationGroups, $isArray, $this->depth + 1);
        }

        return $type;
    }

    private function dataTypeIsClassname(string $type): bool
    {
        return strpos($type, 'App\Entity') !== false;
    }

    private function dataTypeIsObjectArray(string $type): bool
    {
        return strpos($type, 'array<') === 0;
    }

    private function getClassnameFromObjectArrayType(string $type): string
    {
        return rtrim(ltrim($type, 'array<'), '>');
    }
}
