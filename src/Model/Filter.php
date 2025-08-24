<?php

namespace App\Model;

use App\Enum\FilterType;
use Pimcore\Model\Exception\NotFoundException;

class Filter extends \Pimcore\Model\AbstractModel
{
    public ?int $id = null;
    public ?int $productId = null;
    public ?int $categoryId = null;
    public ?\SplObjectStorage $color = null;
    public ?FilterType $type = null;

    /**
     * Get filter by id
     */
    public static function getById(int $id): ?self
    {
        try {
            $obj = new self;
            $obj->getDao()->getById($id);
            return $obj;
        }
        catch (NotFoundException $ex) {
            \Pimcore\Logger::debug("Filter with id $id not found");
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

    public function getProductId(): ?int
    {
        return $this->productId;
    }

    public function setProductId(int $productId): static
    {
        $this->productId = $productId;

        return $this;
    }

    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    public function setCategoryId(int $categoryId): static
    {
        $this->categoryId = $categoryId;

        return $this;
    }

    public function getColors(): ?\SplObjectStorage
    {
        return $this->color;
    }

    public function addColor(?\App\Model\Color $color): static
    {
        if (is_null($this->color)) {
            $this->color = new \SplObjectStorage();
        }

        if (!$this->color->contains($color)) {
            $this->color->attach($color);
        }

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type->value;
    }

    public function setType(int $type): static
    {
        $this->type = FilterType::from($type);

        return $this;
    }
}
