<?php

declare(strict_types=1);

use App\Module\User\Asset\RequestAsset;
use App\Module\User\Form\RequestForm;
use Yii\Extension\Fontawesome\Dev\Css\NpmAllAsset;
use Yiisoft\Assets\AssetManager;
use Yiisoft\Form\Widget\Field;
use Yiisoft\Form\Widget\Form;
use Yiisoft\Html\Html;
use Yiisoft\Router\UrlGeneratorInterface;

$this->params['breadcrumbs'] = 'Recover your password.';
$this->setTitle($this->params['breadcrumbs']);

/**
 * @var string $action
 * @var AssetManager $assetManager
 * @var string|null $csrf
 * @var RequestForm $data
 * @var Field $field
 * @var UrlGeneratorInterface $url
 */

$assetManager->register([
    NpmAllAsset::class,
    RequestAsset::class
]);

?>

<div class = 'column is-4 is-offset-4'>

    <p class="title has-text-black">
        Recover your password.
    </p>

    <p class="subtitle has-text-black">
        Please fill out the following.
    </p>

    <hr class='mb-2'/>

    <?= Form::begin()
        ->action($action)
        ->options(
            [
                'id' => 'form-recovery-request',
                'class' => 'forms-recovery-request bg-white shadow-md rounded px-8 pb-8',
                'csrf' => $csrf
            ]
        )
        ->start() ?>

        <?= $field->config($data, 'email')->textInput(['tabindex' => '1']) ?>

        <div class = 'flex items-center justify-between'>
            <?= Html::submitButton(
                'Request Password',
                [
                    'class' => 'button is-block is-info is-fullwidth',
                    'name' => 'request-button',
                    'tabindex' => '2'
                ]
            ) ?>

            <hr class='mb-2'/>
        </div>

        <div class = 'text-center pt-3'>
            <?= Html::a(
                'Already registered - Sign in!',
                $url->generate('auth/login'),
                ['tabindex' => '3']
            ) ?>
        </div>

    <?php Form::end() ?>

</div>
