<?php

declare(strict_types=1);

use App\Service\ParameterService;
use Yiisoft\Html\Html;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Router\UrlMatcherInterface;
use Yiisoft\Yii\Bulma\Nav;
use Yiisoft\Yii\Bulma\NavBar;
use Yiisoft\User\User;

/**
 * @var ParameterService $app
 * @var User $identity
 * @var UrlGeneratorInterface $url
 * @var UrlMatcherInterface $urlMatcher
 */

$menuItems = [];

$brand = <<<HTML
<div class="navbar-brand">
    <a class="navbar-item is-hidden-desktop jb-aside-mobile-toggle">
        <span class="icon"><i class="mdi mdi-forwardburger mdi-24px"></i></span>
    </a>
</div>
<div class="navbar-brand is-right">
    <a class="navbar-item is-hidden-desktop jb-navbar-menu-toggle" data-target="w1-navbar-Menu">
        <span class="icon"><i class="mdi mdi-dots-vertical"></i></span>
    </a>
</div>
HTML;

$navConfig = $app->get('navBar.config');

if (isset($identity) && $identity->getId() !== null) {
    $navConfig['brand()'] = [$brand];
    $navConfig['menuOptions()'] = [['class' => 'navbar-menu fadeIn animated faster', 'id' => 'navbar-menu']];
    $navConfig['options()'] = [['class' => 'navbar is-fixed-top']];
    $navConfig['toggleIcon()'] = ['<span class="icon"><i class="mdi mdi-forwardburger mdi-24px"></i></span>'];
    $navConfig['toggleOptions()'] = [['class' => 'navbar-item is-hidden-desktop jb-aside-mobile-toggle']];

    if ($app->get('nav.logged') !== []) {
        $menuItems = $app->get('nav.logged');
    }

    $label = '';

    foreach ($menuItems as $key => $item) {
        $label = strtr($item['label'], [
            '{logo}' => Html::img('/images/avatars/' . $identity->getId() . '.svg'),
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

<?= NavBar::widget($navConfig)->begin() ?>

    <?= Nav::widget()
        ->currentPath($url->generate($urlMatcher->getCurrentRoute()->getName()))
        ->items($menuItems)
    ?>

<?= NavBar::end();
