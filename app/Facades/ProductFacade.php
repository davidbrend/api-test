<?php

namespace App\Facades;

use App\Api\V1\Products\Models\Request\ProductNewRequest;
use App\Api\V1\Products\Models\Request\ProductUpdateRequest;
use App\Api\V1\Products\Models\Response\ProductResponse;
use App\Base\Database\EntityManagerDecorator;
use App\Models\Product;
use DateTime;

class ProductFacade
{
    public function __construct(protected EntityManagerDecorator $em)
    {
    }


    /**
     * @param array<mixed> $criteria
     * @param array<string, string> $orderBy
     * @param int $limit
     * @param int $offset
     * @return array<ProductResponse>
     */
    public function getAllProducts(array $criteria = [], array $orderBy = ['id' => 'ASC'], int $limit = 10, int $offset = 0): array
    {
        $entities = $this->em->getProductRepository()->findAllProducts($criteria, $orderBy, $limit, $offset);

        $result = [];
        foreach ($entities as $entity) {
            $result[] = ProductResponse::from($entity);
        }

        return $result;
    }

    /**
     * @throws \Exception
     */
    public function getProductById(int $id): ProductResponse
    {
        $entity = $this->em->getProductRepository()->findOneBy(['id' => $id]) ?? throw new \Exception('Not found');
        return ProductResponse::from($entity);
    }

    /**
     * @param ProductNewRequest $productRequest
     * @return ProductResponse
     */
    public function createNewProductFromRequest(ProductNewRequest $productRequest): ProductResponse
    {
        $entity = Product::createProductFromRequest($productRequest);

        $this->em->persist($entity);
        $this->em->flush();

        return ProductResponse::from($entity);
    }

    /**
     * @throws \Exception
     */
    public function updateProductByEntity(ProductUpdateRequest $entity): ProductResponse
    {
        $product = $this->em->getProductRepository()->findOneBy(['id' => $entity->id]);
        if ($product === null) {
            throw new \Exception('Invalid product');
        }

        $product->setPrice($entity->price)
            ->setName($entity->name)
            ->setUpdatedAt(new DateTime());

        $this->em->persist($product);
        $this->em->flush();

        return ProductResponse::from($product);
    }

    /**
     * @throws \Exception
     */
    public function deleteProductById(int $id): ProductResponse
    {
        $product = $this->em->getProductRepository()->getUndeletedProduct($id);
        if ($product === null) {
            throw new \Exception('Invalid product');
        }

        $product->setDeletedAt(new DateTime());
        $this->em->persist($product);
        $this->em->flush();

        return ProductResponse::from($product);
    }
}