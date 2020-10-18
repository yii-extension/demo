<?php

declare(strict_types=1);

use App\Module\User\Asset\RegisterAsset;
use App\Module\User\Form\RegisterForm;
use Yiisoft\Assets\AssetManager;
use Yiisoft\Form\Widget\Field;
use Yiisoft\Form\Widget\Form;
use Yiisoft\Html\Html;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Router\UrlMatcherInterface;

/**
 * @var AssetManager $assetManager
 * @var string|null $csrf
 * @var RegisterForm $data
 * @var Field $field
 */
$assetManager->register([
    ItemCreateAsset::class
]);

$this->setTitle($title);
?>

<div class = 'column is-4 is-offset-4'>

    <p class = 'title has-text-black'>
        <?= $title ?>
    </p>

    <p class="subtitle has-text-black">
        Please fill out the following.
    </p>

    <hr class = 'mb-2'></hr>

    <?= Form::begin()
        ->action($action)
        ->options(
            [
                'id' => 'form-admin-create',
                'class' => 'forms-admin-create bg-white shadow-md rounded px-8 pb-8',
                'csrf' => $csrf
            ]
        )
        ->start() ?>

    <?php Form::end() ?>

</div>
