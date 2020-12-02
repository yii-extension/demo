<?php

declare(strict_types=1);

namespace App\Module\User\Action;

use App\Module\User\Form\SettingsForm;
use App\Module\User\Repository\ModuleSettingsRepository;
use App\Service\ViewService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Session\Flash\Flash;

final class SettingsMailerAction
{
    public function mailer(
        Flash $flash,
        ModuleSettingsRepository $settings,
        SettingsForm $settingsForm,
        ServerRequestInterface $request,
        UrlGeneratorInterface $url,
        ViewService $view
    ): ResponseInterface {
        $body = $request->getParsedBody();
        $method = $request->getMethod();

        $settings->loadData($settingsForm);

        if (
            $method === 'POST'
            && $settingsForm->load($body)
            && $settingsForm->validate()
            && $settings->update($settingsForm)
        ) {
            $flash->add(
                'is-info',
                [
                    'header' => $settingsForm->getMessageHeader(),
                    'body' =>  'The settings has been updated.'
                ],
                true
            );
        }

        return $view
            ->viewPath('@user/resources/views')
            ->renderWithLayout(
                '/settings/mailer',
                [
                    'action' => $url->generate('settings/mailer'),
                    'data' => $settingsForm
                ]
            );
    }
}
