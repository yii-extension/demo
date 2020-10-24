<?php

declare(strict_types=1);

use Yiisoft\Html\Html;

/**
 * @var string $name
 * @var string $content
 * @var array $params
 */
?>

<?= Html::encode($params['body']) ?>

<p><?= Html::encode($params['username']) ?></p>
