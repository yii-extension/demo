<?php

declare(strict_types=1);

namespace App\Module\Rbac\Action;

use App\Module\User\Repository\ModuleSettingsRepository;
use App\Service\ViewService;
use App\Service\WebControllerService;
use App\Module\Rbac\Form\ItemForm;
use App\Module\Rbac\Repository\ItemRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Router\UrlGeneratorInterface;

final class ItemCreateAction
{
    public function create(
        ItemForm $itemForm,
        ItemRepository $itemRepository,
        ServerRequestInterface $request,
        ModuleSettingsRepository $settings,
        UrlGeneratorInterface $url,
        ViewService $view,
        WebControllerService $webController
    ): ResponseInterface {
        $body = $request->getParsedBody();
        $method = $request->getMethod();

        if (
            $method === 'POST'
            && $itemForm->load($body)
            && $itemForm->validate()
            && $itemRepository->create($itemForm)
        ) {
            return $webController
                ->withFlash(
                    'is-info',
                    $settings->getMessageHeader(),
                    'The data has been saved.'
                )
                ->redirectResponse('item/index');
        }

        return $view
            ->viewPath('@rbac/resources/views')
            ->renderWithLayout(
                '/item/_form',
                ['action' => $url->generate('item/create'), 'data' => $itemForm, 'title' => 'Create item.']
            );
    }
}
