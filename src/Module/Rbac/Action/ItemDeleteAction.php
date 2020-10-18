<?php

declare(strict_types=1);

namespace App\Module\Rbac\Action;

use App\Service\View;
use App\Module\Rbac\Repository\ItemRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\DataResponse\DataResponseFactoryInterface;
use Yiisoft\Router\UrlGeneratorInterface;

final class ItemDeleteAction
{
    public function delete(
        ItemRepository $itemRepository,
        ServerRequestInterface $request,
        DataResponseFactoryInterface $responseFactory,
        UrlGeneratorInterface $url,
        View $view
    ): ResponseInterface {
        $body = $request->getParsedBody();
        $method = $request->getMethod();
        $id = $request->getAttribute('id');

        $item = $itemRepository->findItemById($id);

        $item->delete();

        $view->addFlash(
            'is-info',
            'System Notification - Yii Demo User Module AR.',
            'The data has been delete.'
        );

        return $responseFactory
            ->createResponse(302)
            ->withHeader('Location', $url->generate('item/index'));
    }
}
