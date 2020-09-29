<?php

declare(strict_types=1);

use App\Module\User\Asset\Resend as ResendAsset;
use Yii\Extension\Fontawesome\Dev\Css\NpmAllAsset;
use Yiisoft\Form\Widget\Field;
use Yiisoft\Form\Widget\Form;
use Yiisoft\Html\Html;

$this->params['breadcrumbs'] = 'Resend confirmation message';
$this->setTitle($this->params['breadcrumbs']);

$assetManager->register([
    NpmAllAsset::class,
    ResendAsset::class
]);
?>

<div class = 'column is-4 is-offset-4'>

    <p class = 'subtitle has-text-black'>
            Please fill out the following fields <br> Resend confirmation message.
    </p>

    <hr class = 'mb-2'></hr>

    <?= Form::begin()
        ->action($url->generate('registration/resend'))
        ->options(
            [
                'id' => 'form-registration-resend',
                'class' => 'forms-registration-resend bg-white shadow-md rounded px-8 pb-8',
                'csrf' => $csrf
            ]
        )
        ->start() ?>

        <?= Field::widget($app->get('user.field'))->config($data, 'email') ?>

        <?= Html::submitButton(
            'Continue',
            [
                'class' => 'button is-block is-info is-fullwidth', 'name' => 'resend-button', 'tabindex' => '2'
            ]
        ) ?>

    <?php Form::end(); ?>

    <hr class = 'mb-2'></hr>

    <?php if ($app->get('user.register')) : ?>
        <p class = 'text-center'>
            <?= Html::a('Don\'t have an account - Sign up!', $url->generate('registration/register')) ?>
        </p>
    <?php endif ?>

    <p class = 'mt-3 text-center'>
        <?= Html::a('Already registered - Sign in!', $url->generate('auth/login')) ?>
    </p>

</div>
