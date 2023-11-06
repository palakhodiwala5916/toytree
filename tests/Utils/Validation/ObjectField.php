<?php

namespace App\Tests\Utils\Validation;

use Doctrine\Common\Collections\ArrayCollection;

class ObjectField
{
    public string $name;
    public bool   $isRequired;

    /**
     * @var string|ObjectSchema|ObjectSchema[]|ArrayCollection
     */
    public $type;

    /**
     * @param string|ObjectSchema|ObjectSchema[]|ArrayCollection $type
     */
    public function __construct(string $name, $type, bool $isRequired = true)
    {
        $this->name = $name;
        $this->type = $type;
        $this->isRequired = $isRequired;
    }

    public function __toString()
    {
        return sprintf('%s %s %s',
            $this->type,
            $this->name,
            $this->isRequired ? 'NOT NULL' : 'NULL'
        );
    }

    /**
     * @return ArrayCollection|ObjectField[]
     */
    public static function fields_from_array(array $config): ArrayCollection
    {
        $fields = new ArrayCollection();
        foreach ($config as $row) {
            $fields->add(new ObjectField($row[0], $row[1], $row[2] ?? true));
        }
        return $fields;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->isRequired;
    }

    /**
     * @param bool $isRequired
     */
    public function setIsRequired(bool $isRequired): void
    {
        $this->isRequired = $isRequired;
    }

    /**
     * @return ObjectSchema|ObjectSchema[]|ArrayCollection|string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param ObjectSchema|ObjectSchema[]|ArrayCollection|string $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

}
