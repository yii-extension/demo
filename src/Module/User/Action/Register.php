<?php

declare(strict_types=1);

namespace App\Module\User\Action;

use RuntimeException;
use App\Module\User\Repository\UserRepository;
use App\Service\Parameters;
use App\Service\View;
use App\Module\User\Form\Registration as RegistrationForm;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Auth\IdentityRepositoryInterface;
use Yiisoft\DataResponse\DataResponseFactoryInterface;
use Yiisoft\Router\UrlGeneratorInterface;

final class Register
{
    public function register(
        Parameters $app,
        IdentityRepositoryInterface $identityRepository,
        RegistrationForm $registrationForm,
        ServerRequestInterface $request,
        DataResponseFactoryInterface $responseFactory,
        UrlGeneratorInterface $url,
        View $view
    ): ResponseInterface {
        $body = $request->getParsedBody();
        $method = $request->getMethod();
        $registrationForm->ip($request->getServerParams()['REMOTE_ADDR']);

        /** @var UserRepository $identityRepository */
        if (
            $method === 'POST'
            && $registrationForm->load($body)
            && $registrationForm->validate()
            && $identityRepository->register()
        ) {
            /** @var UserRepository $identityRepository */
            if ($identityRepository->sendMailer()) {
                $view->addFlash(
                    'is-info',
                    $app->get('user.messageHeader'),
                    $app->get('user.confirmation')
                        ? 'Please check your email to activate your username.'
                        : 'Your account has been created.'
                );

                return $responseFactory
                    ->createResponse(302)
                    ->withHeader('Location', $url->generate('index'));
            }

            $view->addFlash(
                'is-error',
                $app->get('user.messageHeader'),
                'The email could not be sent, please check your settings.'
            );
        }

        if ($app->get('user.registration')) {
            return $view
                ->viewPath('@user/resources/views')
                ->renderWithLayout('/registration/register', ['data' => $registrationForm]);
        }

        throw new RuntimeException('Module register user is disabled in the application configuration.');
    }
}
