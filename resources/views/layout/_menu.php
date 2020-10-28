<?php

declare(strict_types=1);

use App\Service\ParameterService;
use Yiisoft\Aliases\Aliases;
use Yiisoft\Router\FastRoute\UrlGenerator;
use Yiisoft\Router\FastRoute\UrlMatcher;
use Yiisoft\Yii\Bulma\Nav;
use Yiisoft\Yii\Bulma\NavBar;
use Yiisoft\Html\Html;
use Yiisoft\Yii\Web\User\User;

/**
 * @var Aliases $aliases
 * @var ParameterService $app
 * @var User $user
 * @var array $menuItems
 * @var UrlGenerator $url
 * @var UrlMatcher $urlMatcher
 */

if (isset($identity) && $identity->getId() !== null) {
    if ($app->get('nav.logged') !== []) {
        $menuItems = $app->get('nav.logged');
    }

    $label = '';

    foreach ($menuItems as $key => $item) {
        $label = strtr($item['label'], [
            '{logo}' => Html::img($aliases->get('@web/images/icon-avatar.png')),
            '{username}' => $identity->getIdentity()->username
        ]);

        if ($label !== '') {
            $menuItems[$key]['label'] = $label;
        }
    }
} else {
    $menuItems =  $app->get('nav.guest');
}
?>

<?= NavBar::begin($app->get('navBar.config'))->start() ?>

    <?= Nav::widget()
        ->currentPath($url->generate($urlMatcher->getCurrentRoute()->getName()))
        ->items($menuItems)
    ?>

<?= NavBar::end();
