<?php

declare(strict_types=1);

use App\Module\User\Asset\LoginAsset;
use App\Module\User\Form\LoginForm;
use App\Module\User\Repository\ModuleSettingsRepository;
use Yii\Extension\Fontawesome\Dev\Css\NpmAllAsset;
use Yiisoft\Assets\AssetManager;
use Yiisoft\Form\Widget\Field;
use Yiisoft\Form\Widget\Form;
use Yiisoft\Html\Html;
use Yiisoft\Router\UrlGeneratorInterface;

$this->setTitle('Login');

/**
 * @var string $action
 * @var AssetManager $assetManager
 * @var string|null $csrf
 * @var LoginForm $data
 * @var Field $field
 * @var ModuleSettingsRepository $settings
 * @var UrlGeneratorInterface $url
 */

$assetManager->register([
    NpmAllAsset::class,
    LoginAsset::class,
]);

?>

<div class = 'column is-4 is-offset-4'>

    <p class="title has-text-black">
        Login.
    </p>

    <p class="subtitle has-text-black">
        Please fill out the following.
    </p>

    <div class = 'box'>

        <div class = 'buttons justify-center has-margin-bottom-10'>

            <button class = 'button is-medium is-black'>
                <span class = 'icon is-medium'>
                    <i class = 'fab fa-github fa-2x fa-inverse'></i>
                </span>
            </button>

            <button class = 'button is-medium is-black'>
                <span class = 'icon is-medium'>
                    <i class = 'fab fa-yandex fa-2x fa-inverse'></i>
                </span>
            </button>

            <button class = 'button is-medium is-black'>
            <span class = 'icon is-medium'>
                    <i class = 'fab fa-google fa-2x fa-inverse'></i>
                </span>
            </button>

        </div>

        <?= Form::begin()
            ->action($action)
            ->options(
                [
                    'id' => 'form-security-login',
                    'csrf' => $csrf
                ]
            )
            ->start() ?>

            <?= $field->config($data, 'login')->textInput(['tabindex' => '1']) ?>

            <?= $field->config($data, 'password')->passwordInput(['tabindex' => '2']) ?>

            <?= Html::submitButton(
                'Login ' . html::tag('i', '', ['class' => 'fas fa-sign-in-alt', 'aria-hidden' => 'true']),
                [
                    'class' => 'button is-block is-info is-fullwidth',
                    'id' => 'login-button',
                    'tabindex' => '3'
                ]
            ) ?>

        <?= Form::end() ?>

        <?php if ($settings->isPasswordRecovery() === true) : ?>
            <p class = 'has-text-grey has-margin-top-10'>
                <?= Html::a(
                    'Forgot Password',
                    $url->generate('recovery/request'),
                    ['tabindex' => '4']
                ) ?>
            </p>
        <?php endif ?>

        <p class = 'has-text-grey'>
            <?= Html::a(
                'Didn\'t receive confirmation message',
                $url->generate('registration/resend'),
                ['tabindex' => '5']
            ) ?>
        </p>

    </div>

</div>
