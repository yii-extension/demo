<?php

declare(strict_types=1);

namespace App\Service;

use Psr\Http\Message\ResponseInterface;
use Yiisoft\Aliases\Aliases;
use Yiisoft\Assets\AssetManager;
use Yiisoft\Csrf\CsrfToken;
use Yiisoft\DataResponse\DataResponseFactoryInterface;
use Yiisoft\Form\Widget\Field;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Router\UrlMatcherInterface;
use Yiisoft\Session\Flash\Flash;
use Yiisoft\View\ViewContextInterface;
use Yiisoft\View\WebView;
use Yiisoft\Yii\Web\User\User;

final class View implements ViewContextInterface
{
    private Aliases $aliases;
    private Parameters $app;
    private AssetManager $assetManager;
    private CsrfToken $csrf;
    private Flash $flash;
    private Field $field;
    protected DataResponseFactoryInterface $responseFactory;
    private UrlGeneratorInterface $url;
    private UrlMatcherInterface $urlMatcher;
    private User $user;
    private ?string $viewPath = null;
    private WebView $webView;

    public function __construct(
        Aliases $aliases,
        Parameters $app,
        AssetManager $assetManager,
        CsrfToken $csrf,
        Flash $flash,
        Field $field,
        DataResponseFactoryInterface $responseFactory,
        UrlMatcherInterface $urlMatcher,
        UrlGeneratorInterface $url,
        User $user,
        WebView $webView
    ) {
        $this->aliases = $aliases;
        $this->app = $app;
        $this->assetManager = $assetManager;
        $this->csrf = $csrf;
        $this->flash = $flash;
        $this->field = $field;
        $this->responseFactory = $responseFactory;
        $this->url = $url;
        $this->user = $user;
        $this->urlMatcher = $urlMatcher;
        $this->webView = $webView;
    }

    public function renderWithLayout(string $view, array $parameters = []): ResponseInterface
    {
        $parameters = array_merge(
            $parameters,
            [
                'app' => $this->app,
                'assetManager' => $this->assetManager,
                'csrf' => $this->csrf->getValue(),
                'identity' => $this->user,
                'field' => $this->field,
                'url' => $this->url,
                'urlMatcher' => $this->urlMatcher
            ]
        );

        $content = $this->webView->render(
            '//layout/main',
            array_merge(
                [
                    'content' => $this->webView->render($view, $parameters, $this),
                ],
                $parameters
            ),
            $this
        );

        return $this->responseFactory->createResponse($content);
    }

    public function addFlash(string $type, string $header, string $body, bool $removerAfterAccess = true): void
    {
        $this->flash->add(
            $type,
            [
                'header' => $header,
                'body' =>  $body
            ],
            $removerAfterAccess
        );
    }

    public function getViewPath(): string
    {
        if ($this->viewPath === null) {
            $this->viewPath = $this->webView->getBasePath();
        }

        return $this->aliases->get($this->viewPath);
    }

    public function viewPath(string $viewPath): self
    {
        $this->viewPath = $viewPath;

        return $this;
    }
}
