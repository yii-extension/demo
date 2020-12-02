<?php

declare(strict_types=1);

use App\Module\User\Asset\ResendAsset;
use App\Module\User\Form\ResetForm;
use App\Module\User\Repository\ModuleSettingsRepository;
use Yii\Extension\Fontawesome\Dev\Css\NpmAllAsset;
use Yiisoft\Assets\AssetManager;
use Yiisoft\Form\Widget\Field;
use Yiisoft\Form\Widget\Form;
use Yiisoft\Html\Html;
use Yiisoft\Router\UrlGeneratorInterface;

$this->setTitle('Resend confirmation message');

/**
 * @var string $action
 * @var AssetManager $assetManager
 * @var string|null $csrf
 * @var ResetForm $data
 * @var Field $field
 * @var ModuleSettingsRepository $settings
 * @var UrlGeneratorInterface $url
 */

$assetManager->register([
    NpmAllAsset::class,
    ResendAsset::class
]);
?>

<p class="title has-text-black">
    Resend confirmation message.
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
                'id' => 'form-registration-resend',
                'class' => 'forms-registration-resend bg-white shadow-md rounded px-8 pb-8',
                'csrf' => $csrf
            ]
        )
        ->begin() ?>

        <?= $field->config($data, 'email')->textInput(['tabindex' => '1']) ?>

        <?= Html::submitButton(
            'Continue',
            [
                'class' => 'button is-block is-info is-fullwidth', 'name' => 'resend-button', 'tabindex' => '2'
            ]
        ) ?>

    <?php Form::end(); ?>

    <hr class='mb-2'/>

    <?php if ($settings->isRegister()) : ?>
        <p class = 'text-center'>
            <?= Html::a(
                'Don\'t have an account - Sign up!',
                $url->generate('registration/register'),
                ['tabindex' => '3']
            ) ?>
        </p>
    <?php endif ?>

    <p class = 'mt-3 text-center'>
        <?= Html::a(
            'Already registered - Sign in!',
            $url->generate('auth/login'),
            ['tabindex' => '4']
        ) ?>
    </p>

</div>
