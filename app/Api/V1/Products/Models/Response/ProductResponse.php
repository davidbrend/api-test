<?php

namespace App\Api\V1\Products\Models\Response;

use Apitte\Core\Mapping\Response\BasicEntity;
use App\Models\Product;

final class ProductResponse extends BasicEntity
{
    public int $id;
    public string $name;
    public float $price;
    public \DateTime $createdAt;
    public ?\DateTime $updatedAt = null;
    public ?\DateTime $deletedAt = null;

    public static function from(Product $product): self
    {
        $self = new self();
        $self->id = $product->getId();
        $self->name = $product->getName();
        $self->price = $product->getPrice();
        $self->createdAt = $product->getCreatedAt();
        $self->updatedAt = $product->getUpdatedAt();
        $self->deletedAt = $product->getDeletedAt();

        return $self;
    }

}