<?php

declare(strict_types=1);

namespace App\Module\Rbac\Action;

use App\Module\User\Repository\ModuleSettingsRepository;
use App\Service\WebControllerService;
use App\Module\Rbac\Repository\ItemRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ItemDeleteAction
{
    public function delete(
        ItemRepository $itemRepository,
        ServerRequestInterface $request,
        ModuleSettingsRepository $settings,
        WebControllerService $webController
    ): ResponseInterface {
        $id = $request->getAttribute('id');

        if ($id === null || ($item = $itemRepository->findItemById($id)) === null) {
            return $webController->notFoundResponse();
        }

        $item->delete();

        return $webController
            ->withFlash(
                'is-danger',
                $settings->getMessageHeader(),
                'The data has been delete.'
            )
            ->redirectResponse('item/index');
    }
}
