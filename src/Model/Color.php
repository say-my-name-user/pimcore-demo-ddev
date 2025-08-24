<?php

namespace App\Model;

use Pimcore\Model\Exception\NotFoundException;

class Color extends \Pimcore\Model\AbstractModel
{
    public ?int $id = null;
    private ?string $value = null;

    /**
     * Get color by id
     */
    public static function getById(int $id): ?self
    {
        try {
            $obj = new self;
            $obj->getDao()->getById($id);
            return $obj;
        }
        catch (NotFoundException $ex) {
            \Pimcore\Logger::debug("Color with id $id not found");
        }

        return null;
    }

    /**
     * Get colors by filter id
     */
    public static function getByFilterId(int $filterId): ?self
    {
        try {
            $obj = new self;
            $obj->getDao()->getByFilterId($filterId);
            return $obj;
        }
        catch (NotFoundException $ex) {
            \Pimcore\Logger::debug("Colors with filter id $filterId not found");
        }

        return null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $key, mixed $value, bool $ignoreEmptyValues = false): static
    {
        if ($key === 'value') {
            $this->value = $value;
        } else {
            parent::setValue($key, $value, $ignoreEmptyValues);
        }

        return $this;
    }
}
