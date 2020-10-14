<?php

declare(strict_types=1);

use App\Module\User\Asset\Login as LoginAsset;
use App\Module\User\Form\Login;
use App\Module\User\Repository\ModuleSettings as ModuleSettingsRepository;
use Yii\Extension\Fontawesome\Dev\Css\NpmAllAsset;
use Yiisoft\Assets\AssetManager;
use Yiisoft\Form\Widget\Field;
use Yiisoft\Form\Widget\Form;
use Yiisoft\Html\Html;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Router\UrlMatcherInterface;

$this->setTitle('Login');

/**
 * @var AssetManager $assetManager
 * @var string|null $csrf
 * @var Login $data
 * @var Field $field
 * @var ModuleSettingsRepository $settings
 * @var UrlGeneratorInterface $url
 * @var UrlMatcherInterface $urlMatcher
 */

$assetManager->register([
    NpmAllAsset::class,
    LoginAsset::class,
]);

?>

<div class = 'column is-4 is-offset-4'>

    <p class = 'subtitle has-text-black'>
        Please fill out the following to Login.
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
            ->action($url->generate('auth/login'))
            ->options(
                [
                    'id' => 'form-security-login',
                    'csrf' => $csrf
                ]
            )
            ->start() ?>

            <?= $field->config($data, 'login') ?>

            <?= $field->config($data, 'password')->passwordInput() ?>

            <?= Html::submitButton(
                'Login ' . html::tag('i', '', ['class' => 'fas fa-sign-in-alt', 'aria-hidden' => 'true']),
                [
                    'class' => 'button is-block is-info is-fullwidth',
                    'id' => 'login-button',
                    'tabindex' => '4'
                ]
            ) ?>

        <?= Form::end() ?>

        <?php if ($settings->isPasswordRecovery() === true) : ?>
            <p class = 'has-text-grey has-margin-top-10'>
                <?= Html::a('Forgot Password', $url->generate('recovery/request')) ?>
            </p>
        <?php endif ?>

        <p class = 'has-text-grey'>
            <?= Html::a('Didn\'t receive confirmation message', $url->generate('registration/resend')) ?>
        </p>

    </div>

</div>
