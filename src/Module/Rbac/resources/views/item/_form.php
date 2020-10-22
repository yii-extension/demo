<?php

declare(strict_types=1);

use App\Module\Rbac\Asset\ItemCreateAsset;
use App\Module\Rbac\Form\ItemForm;
use Yiisoft\Assets\AssetManager;
use Yiisoft\Form\Widget\Field;
use Yiisoft\Form\Widget\Form;
use Yiisoft\Html\Html;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Router\UrlMatcherInterface;

$this->setTitle('Create Item');


/**
 * @var AssetManager $assetManager
 * @var string|null $csrf
 * @var ItemForm $data
 * @var Field $field
 * @var UrlGeneratorInterface $url
 * @var UrlMatcherInterface $urlMatcher
 */
$assetManager->register([
    ItemCreateAsset::class
]);

$this->setTitle($title);

?>

<p class = 'title has-text-black'>
    <?= $title ?>
</p>

<p class="subtitle has-text-black">
    Please fill out the following.
</p>

<hr class = 'mb-2'></hr>

<div class = 'column is-4 is-offset-4'>

    <?= Form::begin()
        ->action($action)
        ->options(
            [
                'id' => 'form-item-create',
                'class' => 'forms-item-create bg-white shadow-md rounded px-8 pb-8',
                'csrf' => $csrf
            ]
        )
        ->start() ?>

        <?= $field->config($data, 'name')->textInput(['tabindex' => '1']) ?>

        <?= $field->config($data, 'description')->textInput(['tabindex' => '2']) ?>

        <?= $field->config($data, 'type')
            ->dropDownList(
                [
                    '1' => 'Role',
                    '2' => 'Permission'
                ],
                [
                    'prompt' => [
                        'text' => 'Select type item',
                        'options' => [
                            'value' => '0',
                            'selected' => 'selected'
                        ]
                    ],
                    'tabindex' => '3'
                ]
            ) ?>

        <div class = 'flex items-center justify-between'>
            <?= Html::submitButton(
                'Save',
                [
                    'class' => 'button is-block is-info is-fullwidth', 'id' => 'save-button', 'tabindex' => '4'
                ]
            ) ?>

        </div>

    <?php Form::end() ?>

</div>
