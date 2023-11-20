<?php

namespace App\Base\Database;

use App\Models\Product;
use App\Repositories\ProductRepository;
use Doctrine\ORM\Decorator\EntityManagerDecorator as DoctrineEntityManagerDecorator;

final class EntityManagerDecorator extends DoctrineEntityManagerDecorator
{
    public function getProductRepository(): ProductRepository
    {
        /** @phpstan-ignore-next-line */
        return $this->getRepository(Product::class);
    }
}