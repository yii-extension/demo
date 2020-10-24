<?php

declare(strict_types=1);

namespace App\Module\Rbac\Action;

use App\Service\ViewService;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\Router\UrlGeneratorInterface;

final class ItemAction
{
    public function index(UrlGeneratorInterface $url, ViewService $view): ResponseInterface
    {
        return $view
            ->viewPath('@rbac/resources/views')
            ->renderWithLayout('/item/index', ['url' => $url]);
    }
}
