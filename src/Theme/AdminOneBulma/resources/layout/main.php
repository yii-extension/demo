<?php

declare(strict_types=1);

use App\Theme\AdminOneBulma\Asset\AdminOneAsset;
use App\Widget\FlashMessage;
use Yiisoft\Yii\Bulma\Breadcrumbs;
use Yiisoft\Html\Html;

/**
 * @var App\Service\Parameters $app
 * @var Yiisoft\Assets\AssetManager $assetManager
 * @var Yiisoft\View\WebView $this
 * @var Yiisoft\Yii\Web\User\User $identity
 * @var Yiisoft\Router\UrlGeneratorInterface $url
 * @var Yiisoft\Router\UrlMatcherInterface $urlMatcher
 * @var string|null $csrf
 * @var string $content
 */

$assetManager->register([
    AdminOneAsset::class
]);

$this->setCssFiles($assetManager->getCssFiles());
$this->setJsFiles($assetManager->getJsFiles());

$breadCrumbsItems = [];
$class = [
    'lang' => $app->get('app.language')
];
$route = $urlMatcher->getCurrentRoute()->getName();

if (isset($this->params['breadcrumbs']) && $url->generate($route) !== '/') {
    $breadCrumbsItems = [
        ['label' => $this->params['breadcrumbs']]
    ];
}

if (isset($identity) && $identity->getId() !== null) {
    $class['class'] = 'has-aside-left has-aside-mobile-transition has-aside-expanded';
}

?>

<?= $this->beginPage() ?>

    <!DOCTYPE html>

    <?= Html::beginTag('html', $class) ?>

        <?= $this->render('_head', ['app' => $app, 'csrf' => $csrf]) ?>

        <?php $this->beginBody() ?>

            <body>

                <div id = 'app'>

                    <?php if (isset($identity) && $identity->getId() !== null) : ?>
                        <?= $this->render(
                            '_sidebar',
                            ['app' => $app, 'id' => $id ?? null, 'url' => $url, 'urlMatcher' => $urlMatcher]
                        ) ?>
                    <?php endif ?>

                    <section class="hero is-fullheight is-light">
                        <div class = 'hero-head'>
                            <header class = <?= (isset($identity) && $identity->getId() !== null)
                                ? 'navbar' : 'has-background-black' ?>>
                                <div class="container">
                                    <?= $this->render(
                                        '_menu',
                                        [
                                            'app' => $app,
                                            'identity' => $identity,
                                            'url' => $url,
                                            'urlMatcher' => $urlMatcher
                                        ]
                                    ) ?>
                                </div>
                            </header>
                            <div>
                                <?= Breadcrumbs::widget()
                                    ->homeLink(
                                        [
                                            'label' => 'Home',
                                            'url' => '/',
                                            'icon' => 'fas fa-home',
                                            'iconOptions' => 'icon',
                                            'encode' => false
                                        ]
                                    )
                                    ->links($breadCrumbsItems)
                                    ->options(['class' => 'has-succeeds-separator is-centered has-margin-top-20']) ?>
                                <?= FlashMessage::widget() ?>
                            </div>
                        </div>

                        <div class = 'hero-body is-light <?= ($route === "admin/index" || $route === "admin/info" ||
                            $route === "item/index") ? "align-items-flex-start" : ""?>'>
                            <div class = 'container has-text-centered'>
                                <?= $content ?>
                            </div>
                        </div>

                        <div class = 'hero-footer has-background-black'>
                            <?= $this->render('_footer') ?>
                        </div>

                    </section>

                </div>

            </body>

        <?php $this->endBody() ?>

    <?= Html::endTag('html') ?>

<?php $this->endPage();
