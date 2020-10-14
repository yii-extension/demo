<?php

declare(strict_types=1);

use App\Module\User\Asset\Register as RegisterAsset;
use App\Module\User\Form\Register;
use App\Module\User\Repository\ModuleSettings as ModuleSettingsRepository;
use Yiisoft\Assets\AssetManager;
use Yiisoft\Form\Widget\Field;
use Yiisoft\Form\Widget\Form;
use Yiisoft\Html\Html;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Router\UrlMatcherInterface;

$this->setTitle('Register');


 /**
  * @var AssetManager $assetManager
  * @var string|null $csrf
  * @var Register $data
  * @var Field $field
  * @var ModuleSettingsRepository $settings
  * @var UrlGeneratorInterface $url
  * @var UrlMatcherInterface $urlMatcher
  */

$assetManager->register([
    RegisterAsset::class
]);

?>

<div class = 'column is-4 is-offset-4'>

    <p class = 'subtitle has-text-black'>
        Please fill out the following fields <br/> Sign up
    </p>

    <hr class = 'mb-2'></hr>

    <?= Form::begin()
        ->action($url->generate('registration/register'))
        ->options(
            [
                'id' => 'form-registration-register',
                'class' => 'forms-registration-register bg-white shadow-md rounded px-8 pb-8',
                'csrf' => $csrf
            ]
        )
        ->start() ?>

        <?= $field->config($data, 'email') ?>

        <?= $field->config($data, 'username') ?>

        <?php if ($settings->isGeneratingPassword() === false) : ?>
            <?= $field->config($data, 'password')->passwordInput() ?>
        <?php endif ?>

        <div class = 'flex items-center justify-between'>
            <?= Html::submitButton(
                'Register',
                [
                    'class' => 'button is-block is-info is-fullwidth', 'id' => 'login-button', 'tabindex' => '4'
                ]
            ) ?>

        </div>

        <hr class = 'mb-2'></hr>

        <div class = 'text-center pt-3'>
            <?= Html::a('Already registered - Sign in!', $url->generate('auth/login')) ?>
        </div>

    <?php Form::end() ?>

</div>
