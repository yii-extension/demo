<?php

declare(strict_types=1);

/**
 * @var Parameters $app
 * @var Yiisoft\Router\UrlGeneratorInterface $url
 * @var Yiisoft\Router\UrlMatcherInterface $urlMatcher
 * @var User $identity
 */
use App\Service\Parameters;
use Yiisoft\Html\Html;
use Yiisoft\Yii\Bulma\Nav;
use Yiisoft\Yii\Bulma\NavBar;
use Yiisoft\Yii\Web\User\User;

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

$navConfig = $app->get('app.navBar.config');

if (isset($identity) && $identity->getId() !== null) {
    $navConfig['brand()'] = [$brand];
    $navConfig['menuOptions()'] = [['class' => 'navbar-menu fadeIn animated faster', 'id' => 'navbar-menu']];
    $navConfig['options()'] = [['class' => 'navbar is-fixed-top']];
    $navConfig['toggleIcon()'] = ['<span class="icon"><i class="mdi mdi-forwardburger mdi-24px"></i></span>'];
    $navConfig['toggleOptions()'] = [['class' => 'navbar-item is-hidden-desktop jb-aside-mobile-toggle']];

    if ($app->get('app.nav.logged') !== []) {
        $menuItems = $app->get('app.nav.logged');
    }

    $label = '';

    foreach ($menuItems as $key => $item) {
        $label = strtr($item['label'], [
            '{logo}' => $identity->getIdentity()->profile->avatar,
            '{username}' => $identity->getIdentity()->username
        ]);

        if ($label !== '') {
            $menuItems[$key]['label'] = $label;
        }
    }
} else {
    $menuItems =  $app->get('app.nav.guest');
}
?>

<?= NavBar::begin($navConfig)->start() ?>

    <?= Nav::widget()
        ->currentPath($url->generate($urlMatcher->getCurrentRoute()->getName()))
        ->items($menuItems)
    ?>

<?= NavBar::end();
