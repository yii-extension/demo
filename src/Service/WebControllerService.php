<?php

declare(strict_types=1);

namespace App\Service;

use App\Module\User\Repository\ModuleSettingsRepository;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Session\Flash\Flash;
use Yiisoft\User\User;

final class WebControllerService
{
    private User $identity;
    private Flash $flash;
    private ResponseFactoryInterface $responseFactory;
    private ModuleSettingsRepository $settings;
    private UrlGeneratorInterface $url;

    public function __construct(
        User $identity,
        Flash $flash,
        ResponseFactoryInterface $responseFactory,
        ModuleSettingsRepository $settings,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->identity = $identity;
        $this->flash = $flash;
        $this->responseFactory = $responseFactory;
        $this->settings = $settings;
        $this->url = $urlGenerator;
    }

    public function withFlash(string $type, string $header, string $body, bool $removerAfterAccess = true): self
    {
        $this->flash->add(
            $type,
            [
                'header' => $header,
                'body' =>  $body
            ],
            $removerAfterAccess
        );

        return $this;
    }

    public function redirectResponse(string $url): ResponseInterface
    {
        return $this->responseFactory
            ->createResponse(302)
            ->withHeader('Location', $this->url->generate($url));
    }

    public function notFoundResponse(string $body = null): ResponseInterface
    {
        return $this
            ->withFlash(
                'is-danger',
                $this->settings->getMessageHeader(),
                $body ?: '<strong>404</strong> - The requested page does not exist.'
            )
            ->redirectResponse($this->identity->isguest() ? 'index' : 'admin/index');
    }
}
