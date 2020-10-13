<?php

declare(strict_types=1);

/**
 * @var \App\ApplicationParameters $app
 */

use Yiisoft\Yii\Widgets\FragmentCache;

$this->params['breadcrumbs'] = '/';

$this->setTitle($app->get('app.name'));

$cache = FragmentCache::begin()->id('index');

?>

<?php if ($cache->getCachedContent() === null) : ?>
    <?php $cache->start() ?>
    <h1 class="title">Hello World</h1>
    <p class="subtitle">My first website with <strong>Yii 3.0</strong>!</p>
<?php endif ?>

<?= FragmentCache::end() ?>
