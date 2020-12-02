<?php

declare(strict_types=1);

use App\Module\User\Asset\AdminCreateAsset;
use App\Module\User\Form\RegisterForm;
use Yiisoft\Assets\AssetManager;
use Yiisoft\Form\Widget\Field;
use Yiisoft\Form\Widget\Form;
use Yiisoft\Html\Html;

/**
 * @var string $action
 * @var AssetManager $assetManager
 * @var string|null $csrf
 * @var RegisterForm $data
 * @var Field $field
 * @var string $title
 */
$assetManager->register([
    AdminCreateAsset::class
]);

$this->setTitle($title);
?>

<p class = 'title has-text-black'>
    <?= $title ?>
</p>

<p class="subtitle has-text-black">
    Please fill out the following.
</p>

<hr class='mb-2'/>

<div class = 'column is-4 is-offset-4'>

    <?= Form::widget()
        ->action($action)
        ->options(
            [
                'id' => 'form-admin-create',
                'class' => 'forms-admin-create bg-white shadow-md rounded px-8 pb-8',
                'csrf' => $csrf
            ]
        )
        ->begin() ?>

        <?= $field->config($data, 'email')->textInput(['tabindex' => '1']) ?>

        <?= $field->config($data, 'username')->textInput(['tabindex' => '2']) ?>

        <?= $field->config($data, 'password')
            ->passwordInput(['tabindex' => '3'])
            ->hint('Password will be generated automatically if not provided.')
        ?>

        <hr class='mb-2'/>

        <div class = 'flex items-center justify-between'>
            <?= Html::submitButton(
                'Save',
                [
                    'class' => 'button is-block is-info is-fullwidth', 'id' => 'save-button', 'tabindex' => '4'
                ]
            ) ?>

        </div>

        <div class='notification is-success is-light'>
            Credentials will be sent to the user by email.
        </div>

    <?= Form::end() ?>

</div>
