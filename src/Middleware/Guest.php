<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Service\ParameterService;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\User\User;

final class Guest implements MiddlewareInterface
{
    private ParameterService $app;
    private UrlGeneratorInterface $url;
    private User $identity;
    private ResponseFactoryInterface $responseFactory;

    public function __construct(
        ParameterService $app,
        UrlGeneratorInterface $url,
        User $identity,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->app = $app;
        $this->identity = $identity;
        $this->responseFactory = $responseFactory;
        $this->url = $url;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($this->identity->isGuest() === false) {
            return $this->responseFactory
                ->createResponse(302)
                ->withHeader('Location', $this->url->generate($this->app->get('adminUrl')));
        }

        return $handler->handle($request);
    }
}
