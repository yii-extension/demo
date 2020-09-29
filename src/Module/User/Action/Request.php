<?php

declare(strict_types=1);

namespace App\Module\User\Action;

use RuntimeException;
use App\Module\User\Form\Request as RequestForm;
use App\Module\User\Service\Request as RequestService;
use App\Service\Parameters;
use App\Service\View;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\DataResponse\DataResponseFactoryInterface;
use Yiisoft\Auth\IdentityRepositoryInterface;
use Yiisoft\Router\UrlGeneratorInterface;

final class Request
{
    public function request(
        Parameters $app,
        IdentityRepositoryInterface $identityRepository,
        RequestForm $requestForm,
        ServerRequestInterface $request,
        RequestService $requestService,
        DataResponseFactoryInterface $responseFactory,
        UrlGeneratorInterface $url,
        View $view
    ): ResponseInterface {
        $body = $request->getParsedBody();
        $method = $request->getMethod();

        if (
            $method === 'POST'
            && $requestForm->load($body)
            && $requestForm->validate()
            && $requestService->run($requestForm, $identityRepository)
        ) {
            $view->addFlash(
                'is-info',
                $app->get('user.messageHeader'),
                'Please check your email to change your password.'
            );

            return $responseFactory
                ->createResponse(302)
                ->withHeader('Location', $url->generate('index'));
        }

        if ($app->get('user.passwordRecovery')) {
            return $view
                ->viewPath('@user/resources/views')
                ->renderWithLayout('/recovery/request', ['data' => $requestForm]);
        }

        throw new RuntimeException('Module no enabled');
    }
}
