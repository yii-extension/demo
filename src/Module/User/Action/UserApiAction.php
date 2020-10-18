<?php

declare(strict_types=1);

namespace App\Module\User\Action;

use App\Module\User\Api\UserApi;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\DataResponse\DataResponseFactoryInterface;

final class UserApiAction
{
    public function index(
        UserApi $userApi,
        DataResponseFactoryInterface $responseFactory
    ): ResponseInterface {
        return $responseFactory->createResponse($userApi->all());
    }
}
