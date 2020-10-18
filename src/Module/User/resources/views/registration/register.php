<?php

declare(strict_types=1);

use App\Module\User\Asset\RegisterAsset;
use App\Module\User\Form\RegisterForm;
use App\Module\User\Repository\ModuleSettingsRepository;
use Yiisoft\Assets\AssetManager;
use Yiisoft\Form\Widget\Field;
use Yiisoft\Form\Widget\Form;
use Yiisoft\Html\Html;
use Yiisoft\Router\UrlGeneratorInterface;

$this->setTitle('Register');

 /**
  * @var string $action
  * @var AssetManager $assetManager
  * @var string|null $csrf
  * @var RegisterForm $data
  * @var Field $field
  * @var ModuleSettingsRepository $settings
  * @var UrlGeneratorInterface $url
  */

$assetManager->register([
    RegisterAsset::class
]);

?>

<div class = 'column is-4 is-offset-4'>

    <p class="title has-text-black">
        Sign up.
    </p>

    <p class="subtitle has-text-black">
        Please fill out the following.
    </p>

    <hr class='mb-2'/>

    <?= Form::begin()
        ->action($action)
        ->options(
            [
                'id' => 'form-registration-register',
                'class' => 'forms-registration-register bg-white shadow-md rounded px-8 pb-8',
                'csrf' => $csrf
            ]
        )
        ->start() ?>

        <?= $field->config($data, 'email')->textInput(['tabindex' => '1']) ?>

        <?= $field->config($data, 'username')->textInput(['tabindex' => '2']) ?>

        <?php if ($settings->isGeneratingPassword() === false) : ?>
            <?= $field->config($data, 'password')->passwordInput(['tabindex' => '3']) ?>
        <?php endif ?>

        <div class = 'flex items-center justify-between'>
            <?= Html::submitButton(
                'Register',
                [
                    'class' => 'button is-block is-info is-fullwidth', 'id' => 'login-button', 'tabindex' => '4'
                ]
            ) ?>

        </div>

        <hr class='mb-2'/>

        <div class = 'text-center pt-3'>
            <?= Html::a(
                'Already registered - Sign in!',
                $url->generate('auth/login'),
                ['tabindex' => '5']
            ) ?>
        </div>

    <?php Form::end() ?>

</div>
