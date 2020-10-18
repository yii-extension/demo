<?php

declare(strict_types=1);

namespace App\Module\Rbac\Action;

use App\Module\Rbac\Api\ItemApi;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\DataResponse\DataResponseFactoryInterface;

final class ItemsApiAction
{
    public function index(
        ItemApi $itemApi,
        DataResponseFactoryInterface $responseFactory
    ): ResponseInterface {
        return $responseFactory->createResponse($itemApi->all());
    }
}
