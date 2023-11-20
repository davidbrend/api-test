<?php

namespace App\Repositories;

use App\Base\Database\BaseRepository;
use App\Models\Product;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

/**
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[] findAll()
 * @method Product[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends BaseRepository
{

    /**
     * @param array<mixed> $criteria
     * @param array<string, string> $orderBy
     * @param int $limit
     * @param int $offset
     * @return Product[]
     */
    public function findAllProducts(array $criteria, array $orderBy, int $limit, int $offset): array
    {
        return $this->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function getUndeletedProduct(int $id): ?Product
    {
        $query = $this->getEntityManager()->createQueryBuilder();
        return $query->select('p')->from(Product::class, 'p')
            ->where('p.id = :id')
            ->andWhere('p.deletedAt is null')
            ->setParameter('id',  $id)->getQuery()->getSingleResult();
    }

}