<?php

declare(strict_types=1);

use App\Module\User\Asset\ResetAsset;
use App\Module\User\Form\ResetForm;
use Yiisoft\Assets\AssetManager;
use Yiisoft\Form\Widget\Field;
use Yiisoft\Form\Widget\Form;
use Yiisoft\Html\Html;

$this->params['breadcrumbs'] = 'Reset your password.';
$this->setTitle($this->params['breadcrumbs']);

/**
 * @var string $action
 * @var AssetManager $assetManager
 * @var string $code
 * @var string|null $csrf
 * @var ResetForm $data
 * @var Field $field
 * @var string $id
 */

$assetManager->register([
    ResetAsset::class
]);

?>

<p class="title has-text-black">
    Reset your password.
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
                'id' => 'form-recovery-reset',
                'class' => 'forms-recovery-reset bg-white shadow-md rounded px-8 pb-8',
                'csrf' => $csrf
            ]
        )
        ->begin() ?>

        <?= $field->config($data, 'password')->passwordInput(['tabindex' => '1']) ?>

        <?= Html::submitButton('Reset Password', [
            'class' => 'button is-block is-info is-fullwidth',
            'name' => 'reset-button',
            'tabindex' => '2'
        ]) ?>

    <?php Form::end() ?>

</div>
