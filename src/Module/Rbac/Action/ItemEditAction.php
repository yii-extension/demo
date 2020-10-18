<?php

declare(strict_types=1);

namespace App\Module\Rbac\Action;

use App\Service\View;
use App\Module\Rbac\Form\ItemForm;
use App\Module\Rbac\Repository\ItemRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\DataResponse\DataResponseFactoryInterface;
use Yiisoft\Router\UrlGeneratorInterface;

final class ItemEditAction
{
    public function edit(
        ItemForm $itemForm,
        ItemRepository $itemRepository,
        ServerRequestInterface $request,
        DataResponseFactoryInterface $responseFactory,
        UrlGeneratorInterface $url,
        View $view
    ): ResponseInterface {
        $body = $request->getParsedBody();
        $method = $request->getMethod();
        $id = $request->getAttribute('id');

        $itemRepository->loadData($itemForm, $id);

        if (
            $method === 'POST'
            && $itemForm->load($body)
            && $itemForm->validate()
            && $itemRepository->update($itemForm, $id) === 1
        ) {
            $view->addFlash(
                'is-info',
                'System Notification - Yii Demo User Module AR.',
                'The data has been saved.'
            );

            return $responseFactory
                ->createResponse(302)
                ->withHeader('Location', $url->generate('item/index'));
        }

        return $view
            ->viewPath('@rbac/resources/views')
            ->renderWithLayout(
                '/item/_form',
                ['action' => $url->generate('item/edit', ['id' => $id]), 'data' => $itemForm, 'title' => 'Edit item.'],
                ['id' => $id]
            );
    }
}
