<?php

namespace App\Models;

use App\Api\V1\Products\Models\Request\ProductNewRequest;
use App\Base\Database\BaseEntity;
use App\Repositories\ProductRepository;
use DateTime;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;


#[Entity(repositoryClass: ProductRepository::class)]
#[Table(name: 'products')]
class Product extends BaseEntity
{
    #[Column(type: 'integer')]
    #[Id]
    #[GeneratedValue(strategy: 'IDENTITY')]
    protected int $id;
    #[Column(length: 100)]
    protected string $name;
    #[Column(type: 'float', precision: 10, scale: 2)]
    protected float $price;
    #[Column]
    protected DateTime $createdAt;
    #[Column(nullable: true)]
    protected ?DateTime $updatedAt = null;
    #[Column(nullable: true)]
    protected ?DateTime $deletedAt = null;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Product
    {
        $this->name = $name;
        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): Product
    {
        $this->price = $price;
        return $this;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): Product
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTime $updatedAt): Product
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getDeletedAt(): ?DateTime
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?DateTime $deletedAt): Product
    {
        $this->deletedAt = $deletedAt;
        return $this;
    }

    public static function createProductFromRequest(ProductNewRequest $productRequest): self
    {
        $entity = new self();
        $entity->setName($productRequest->name)
        ->setPrice($productRequest->price)
        ->setCreatedAt(new DateTime());
        return $entity;
    }
}