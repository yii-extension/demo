<?php

declare(strict_types=1);

namespace App\Module\User\Action;

use App\Service\View;
use Psr\Http\Message\ResponseInterface;

final class AdminAction
{
    public function index(View $view): ResponseInterface
    {
        return $view
            ->viewPath('@user/resources/views')
            ->renderWithLayout('/admin/index');
    }
}
