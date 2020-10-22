<?php

declare(strict_types=1);

namespace App\Module\User\Action;

use RuntimeException;
use App\Module\User\Repository\ModuleSettingsRepository;
use App\Module\User\Repository\UserRepository;
use App\Service\View;
use App\Module\User\Form\RegisterForm;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\DataResponse\DataResponseFactoryInterface;
use Yiisoft\Router\UrlGeneratorInterface;

final class RegisterAction
{
    public function register(
        RegisterForm $registerForm,
        ServerRequestInterface $request,
        DataResponseFactoryInterface $responseFactory,
        ModuleSettingsRepository $settings,
        UrlGeneratorInterface $url,
        UserREpository $userRepository,
        View $view
    ): ResponseInterface {
        $body = $request->getParsedBody();
        $method = $request->getMethod();
        $registerForm->ip($request->getServerParams()['REMOTE_ADDR']);

        if (
            $method === 'POST'
            && $registerForm->load($body)
            && $registerForm->validate()
            && $userRepository->register(
                $registerForm,
                $settings->isConfirmation(),
                $settings->isGeneratingPassword()
            )
        ) {
            if (
                $userRepository->sendMailer(
                    $url,
                    $settings->getSubjectWelcome(),
                    ['html' => 'welcome', 'text' => 'text/welcome'],
                    $settings->isConfirmation(),
                    $settings->isGeneratingPassword()
                )
            ) {
                $view->addFlash(
                    'is-info',
                    $settings->getMessageHeader(),
                    $settings->isConfirmation()
                        ? 'Please check your email to activate your username.'
                        : 'Your account has been created.'
                );

                return $responseFactory
                    ->createResponse(302)
                    ->withHeader('Location', $url->generate('index'));
            }

            $view->addFlash(
                'is-error',
                $settings->getMessageHeader(),
                'The email could not be sent, please check your settings.'
            );
        }

        if ($settings->isRegister()) {
            return $view
                ->viewPath('@user/resources/views')
                ->renderWithLayout(
                    '/registration/register',
                    [
                        'action' => $url->generate('registration/register'),
                        'body' => $body,
                        'data' => $registerForm,
                        'settings' => $settings,
                        'url' => $url
                    ]
                );
        }

        throw new RuntimeException('Module register user is disabled in the application configuration.');
    }
}
