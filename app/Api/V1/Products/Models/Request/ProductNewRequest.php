<?php

namespace App\Api\V1\Products\Models\Request;

use Apitte\Core\Mapping\Request\BasicEntity;
use Symfony\Component\Validator\Constraints\NotBlank;

final class ProductNewRequest extends BasicEntity
{
    #[NotBlank]
    public string $name;
    #[NotBlank]
    public float $price;
}