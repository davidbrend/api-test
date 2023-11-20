<?php

namespace App\Api\V1\Products\Models\Request;

use Apitte\Core\Mapping\Request\BasicEntity;
use Symfony\Component\Validator\Constraints\NotBlank;

final class ProductUpdateRequest extends BasicEntity
{
    #[NotBlank]
    public int $id;
    #[NotBlank]
    public string $name;
    #[NotBlank]
    public float $price;
}