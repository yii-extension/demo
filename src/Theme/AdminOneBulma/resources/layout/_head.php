<?php

declare(strict_types=1);

use App\Service\ParameterService;
use Yiisoft\Html\Html;

/**
 * @var ParameterService $app
 * @var string $csrf
 */
?>
<head>
    <meta charset = <?= $app->get('charset') ?>>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name = 'csrf' content = <?= $csrf ?>>

    <title><?= Html::encode($this->getTitle()) ?></title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <?php $this->head() ?>
</head>
