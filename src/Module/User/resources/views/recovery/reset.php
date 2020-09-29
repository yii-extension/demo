<?php

declare(strict_types=1);

use App\Module\User\Asset\Reset as ResetAsset;
use App\Module\User\Form\Reset;
use App\Service\Parameters;
use Yiisoft\Assets\AssetManager;
use Yiisoft\Form\Widget\Field;
use Yiisoft\Form\Widget\Form;
use Yiisoft\Html\Html;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Router\UrlMatcherInterface;

$this->params['breadcrumbs'] = 'Reset your password.';
$this->setTitle($this->params['breadcrumbs']);

/**
 * @var string $id
 * @var string $code
 * @var Parameters $app
 * @var AssetManager $assetManager
 * @var string|null $csrf
 * @var Reset $data
 * @var UrlGeneratorInterface $url
 * @var UrlMatcherInterface $urlMatcher
 */

$assetManager->register([
    ResetAsset::class
]);

?>

<div class = 'column is-4 is-offset-4'>

    <p class = 'subtitle has-text-black'>
        Please fill out the following fields to <br/> Reset your password.
    </p>

    <hr class = 'mb-2'></hr>

    <?= Form::begin()
        ->action($url->generate('recovery/reset', ['id' => $id, 'code' => $code]))
        ->options(
            [
                'id' => 'form-recovery-reset',
                'class' => 'forms-recovery-reset bg-white shadow-md rounded px-8 pb-8',
                'csrf' => $csrf
            ]
        )
        ->start() ?>

        <?= Field::widget($app->get('user.field'))->config($data, 'password')->passwordInput() ?>

        <?= Html::submitButton('Reset Password', [
            'class' => 'button is-block is-info is-fullwidth',
            'name' => 'reset-button',
            'tabindex' => '2'
        ]) ?>

    <?php Form::end() ?>

</div>
