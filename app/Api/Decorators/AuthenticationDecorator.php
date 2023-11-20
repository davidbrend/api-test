<?php

namespace App\Api\Decorators;

use Apitte\Core\Decorator\IRequestDecorator;
use Apitte\Core\Exception\Api\ClientErrorException;
use Apitte\Core\Http\ApiRequest;
use Apitte\Core\Http\ApiResponse;
use Apitte\Core\Http\RequestAttributes;

class AuthenticationDecorator implements IRequestDecorator
{
    public function decorateRequest(ApiRequest $request, ApiResponse $response): ApiRequest
    {
        $endpoint = $request->getAttribute(RequestAttributes::ATTR_ENDPOINT);

        if ($endpoint->hasTag('OpenApi')) {
            return $request;
        }

        $authHeader = $request->getHeader('Authorization');
        if (count($authHeader) === 0) {
            throw ClientErrorException::create()
                ->withMessage('Missing Authorization')
                ->withCode(401);
        }

        $jwt = str_replace('Bearer ', '', $authHeader);
        if (empty($jwt)) {
            throw ClientErrorException::create()
                ->withMessage('Invalid Bearer token')
                ->withCode(401);
        }

        return $request->withAttribute('jwt', 'test');
    }
}