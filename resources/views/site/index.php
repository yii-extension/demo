<?php

declare(strict_types=1);

use App\Service\ParameterService;
use Yiisoft\Yii\Widgets\FragmentCache;

/**
 * @var ParameterService $app
 */

$this->params['breadcrumbs'] = '/';

$this->setTitle('My Project');

$cache = FragmentCache::widget()->id('index');
$cache->begin();
?>

<?php if ($cache->getCachedContent() === null) : ?>
    <h1 class="title">Hello World</h1>
    <p class="subtitle">My first website with <strong>Yii 3.0</strong>!</p>
<?php endif ?>

<?= $cache::end() ?>
