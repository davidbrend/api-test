<?php

namespace App\Api\V1\Products;

use Apitte\Core\Annotation\Controller\Method;
use Apitte\Core\Annotation\Controller\Path;
use Apitte\Core\Annotation\Controller\RequestBody;
use Apitte\Core\Annotation\Controller\RequestParameter;
use Apitte\Core\Annotation\Controller\Response;
use Apitte\Core\Annotation\Controller\Tag;
use Apitte\Core\Exception\Api\ClientErrorException;
use Apitte\Core\Exception\Api\ServerErrorException;
use Apitte\Core\Http\ApiRequest;
use Apitte\Core\Http\ApiResponse;
use Apitte\Negotiation\Http\ArrayEntity;
use App\Api\V1\BaseV1Controller;
use App\Api\V1\Products\Models\Request\ProductNewRequest;
use App\Api\V1\Products\Models\Request\ProductUpdateRequest;
use App\Api\V1\Products\Models\Response\ProductResponse;
use App\Facades\ProductFacade;
use Nette\Http\IResponse;
use Nette\InvalidStateException;

#[Path('/product')]
#[Tag('Product')]
class ProductController extends BaseV1Controller
{
    public function __construct(protected ProductFacade $productFacade)
    {
    }

    #[Path('/all')]
    #[Method('GET')]
    #[RequestParameter(name:"limit", type:"int", in:"query", required:false, description:"Data limit")]
    #[RequestParameter(name:"offset", type:"int", in:"query", required:false, description:"Data offset")]
    #[Response(description: "Success", code: "200", entity: ProductResponse::class)]
    #[Response(description: "Not found", code: "404")]
    public function getAllProducts(ApiRequest $request, ApiResponse $response): ApiResponse
    {
        $limit = $request->getParameter('limit', 10);
        $offset = $request->getParameter('offset', 0);
        return $response->withStatus(ApiResponse::S200_OK)
            ->withEntity(ArrayEntity::from($this->productFacade->getAllProducts(limit: $limit, offset: $offset)));
    }

    #[Path('/one')]
    #[Method('GET')]
    #[RequestParameter(name: "id", type: "int", in: "query", description: "Product ID")]
    #[Response(description: "Success", code: "200", entity: ProductResponse::class)]
    #[Response(description: "Not found", code: "404")]
    public function getOneProduct(ApiRequest $request, ApiResponse $response): ApiResponse
    {
        try {
            $id = self::toInt($request->getParameter('id'));
            return $response->withStatus(ApiResponse::S200_OK)
                ->withEntity(ArrayEntity::from([$this->productFacade->getProductById($id)]));
        } catch (\Throwable $ex) {
            throw ClientErrorException::create()
                ->withMessage('Product not found')
                ->withCode(IResponse::S404_NotFound);
        }
    }

    #[Path('/new')]
    #[Method('POST')]
    #[Response(description: "Successfully created", code: "201")]
    #[Response(description: "Failed to create", code: "404")]
    #[RequestBody('Create new product by structure', ProductNewRequest::class)]
    public function createProduct(ApiRequest $request, ApiResponse $response): ApiResponse
    {
        /** @var ProductNewRequest $entity */
        $entity = $request->getEntity();
        try {
            return $response->withStatus(IResponse::S201_Created)
                ->withEntity(ArrayEntity::from([$this->productFacade->createNewProductFromRequest($entity)]))
                ->withHeader('Content-Type', 'application/json');
        } catch (\Throwable $e) {
            throw ServerErrorException::create()
                ->withMessage('Cannot create the product')
                ->withPrevious($e);
        }
    }

    #[Path('/update')]
    #[Method('PUT')]
    #[Response(description: "Successfully updated", code: "201")]
    #[Response(description: "Failed to update", code: "404")]
    #[RequestBody('Update product by structure', ProductUpdateRequest::class)]
    public function updateProductById(ApiRequest $request, ApiResponse $response): ApiResponse
    {
        /** @var ProductUpdateRequest $entity */
        $entity = $request->getEntity();
        try {
            return $response->withStatus(IResponse::S201_Created)
                ->withEntity(ArrayEntity::from([$this->productFacade->updateProductByEntity($entity)]))
                ->withHeader('Content-Type', 'application/json');
        } catch (\Throwable $e) {
            throw ServerErrorException::create()
                ->withMessage('Cannot create the product')
                ->withPrevious($e);
        }
    }

    #[Path('/delete')]
    #[Method('DELETE')]
    #[RequestParameter(name: "id", type: "int", in: "query", description: "Product ID")]
    #[Response(description: "Success", code: "200", entity: ProductResponse::class)]
    #[Response(description: "Not found", code: "404")]
    public function deleteProductById(ApiRequest $request, ApiResponse $response): ApiResponse
    {
        $id = self::toInt($request->getParameter('id'));
        try {
            return $response->withStatus(ApiResponse::S200_OK)
                ->withEntity(ArrayEntity::from([$this->productFacade->deleteProductById($id)]));
        } catch (\Throwable $ex) {
            throw ClientErrorException::create()
                ->withMessage('Product not found')
                ->withCode(IResponse::S404_NotFound);
        }
    }

    private static function toInt(mixed $value): int
    {
        if (is_string($value) || is_int($value) || is_float($value)) {
            return (int)$value;
        }

        throw new InvalidStateException('Cannot cast to integer');
    }
}