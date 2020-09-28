<?php

declare(strict_types=1);

/**
 * @var App\Service\Parameters $app
 * @var Yiisoft\Router\UrlGeneratorInterface $url
 * @var Yiisoft\Router\UrlMatcherInterface $urlMatcher
 */

use Yiisoft\Yii\Bulma\Menu;

?>

<?= Menu::widget()
    ->brand($app->get('app.menu.brand'))
    ->currentPath($url->generate($urlMatcher->getCurrentRoute()->getName()))
    ->items($app->get('app.menu.items'))
    ->options(['class' => 'aside is-placed-left is-expanded']);
