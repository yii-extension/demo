<?php

declare(strict_types=1);

namespace App\Module\User\Action;

use App\Service\View;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\Router\UrlGeneratorInterface;

final class AdminAction
{
    public function index(UrlGeneratorInterface $url, View $view): ResponseInterface
    {
        return $view
            ->viewPath('@user/resources/views')
            ->renderWithLayout('/admin/index', ['action' => $url->generate('admin/create')]);
    }
}
