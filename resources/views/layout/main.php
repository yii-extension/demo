<?php

declare(strict_types=1);

use App\Asset\AppAsset;
use App\Service\ParameterService;
use App\Widget\FlashMessage;
use Yiisoft\Assets\AssetManager;
use Yiisoft\Router\FastRoute\UrlGenerator;
use Yiisoft\Router\FastRoute\UrlMatcher;

/**
 * @var ParameterService $app
 * @var AssetManager $assetManager
 * @var string $csrf
 * @var string $content
 * @var UrlGenerator $url
 * @var UrlMatcher $urlMatcher
 */

$assetManager->register([
    AppAsset::class
]);

$this->setCssFiles($assetManager->getCssFiles());
$this->setJsFiles($assetManager->getJsFiles());
?>

<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang = <?= $app->get('language') ?>>

        <?= $this->render('_head', ['app' => $app, 'csrf' => $csrf]) ?>

        <?php $this->beginBody() ?>

            <body>

                <section class = 'hero is-fullheight is-light'>

                    <div class = 'hero-head'>
                        <header class = <?= (isset($identity) && $identity->getId() !== null)
                            ? 'navbar' : 'has-background-black' ?>>
                            <?= $this->render('_menu', ['app' => $app, 'url' => $url, 'urlMatcher' => $urlMatcher]) ?>
                        </header>
                        <div>
                            <?= FlashMessage::widget() ?>
                        </div>
                    </div>

                    <div class = 'hero-body is-light'>
                        <div class = 'container has-text-centered'>
                            <?= $content ?>
                        </div>
                    </div>

                    <div class = 'hero-footer has-background-black'>
                        <?= $this->render('_footer') ?>
                    </div>

                </section>

            </body>

        <?php $this->endBody() ?>

    </html>

<?php $this->endPage();
