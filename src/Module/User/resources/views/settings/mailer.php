<?php

declare(strict_types=1);

use App\Asset\BulmaSwitchAsset;
use App\Module\User\Asset\SettingsAsset;
use App\Module\User\Form\LoginForm;
use App\Module\User\Repository\ModuleSettingsRepository;
use Yii\Extension\Fontawesome\Dev\Css\NpmAllAsset;
use Yiisoft\Assets\AssetManager;
use Yiisoft\Form\Widget\Field;
use Yiisoft\Form\Widget\Form;
use Yiisoft\Html\Html;
use Yiisoft\Router\UrlGeneratorInterface;

$this->setTitle('Settings');

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
    BulmaSwitchAsset::class,
    NpmAllAsset::class,
    SettingsAsset::class
]);

$msgHelp = '';
$iconHelp = <<<HTML

HTML;
?>

<p class="title has-text-black">
    Settings.
</p>

<p class="subtitle has-text-black">
    Module user mailer config.
</p>

<hr class='mb-2'/>

<?= Form::widget()
    ->action($action)
    ->options(
        [
            'id' => 'form-settings-mailer',
            'class' => 'forms-settings-mailer bg-white shadow-md rounded',
            'csrf' => $csrf
        ]
    )
    ->begin() ?>

    <div class = 'columns'>
        <?php $msgHelp = $data->emailFromHelp() ?>
        <?= $field
            ->config($data, 'emailFrom')
            ->enclosedByContainer(true, ['class' => 'column is-half has-text-right'])
            ->template("<span><i class='fas fa-info-circle' title='$msgHelp'></i></span>{label}{input}\n{error}")
            ->label(
                true,
                [
                    'class' => 'has-text-black has-text-weight-bold',
                    'label' => ' Email From ',
                ]
            )
            ->textInput(['class' => 'has-text-right', 'tabindex' => '1']) ?>

        <?php $msgHelp = $data->subjectConfirmHelp() ?>
        <?= $field
            ->config($data, 'subjectConfirm')
            ->enclosedByContainer(true, ['class' => 'column is-half has-text-left'])
            ->template("{label}<span><i class='fas fa-info-circle' title='$msgHelp'></i></span>{input}\n{error}")
            ->label(
                true,
                [
                    'class' => 'has-text-black has-text-weight-bold',
                    'label' => ' Subject confirmation ',
                ]
            )
            ->textInput(['class' => 'has-text-left','tabindex' => '2']) ?>
    </div>

    <div class = 'columns'>
        <?php $msgHelp = $data->subjectPasswordHelp() ?>
        <?= $field
            ->config($data, 'subjectPassword')
            ->template("<span><i class='fas fa-info-circle' title='$msgHelp'></i></span>{label}{input}\n{error}")
            ->enclosedByContainer(true, ['class' => 'column is-half has-text-right'])
            ->label(
                true,
                [
                    'class' => 'has-text-black has-text-weight-bold',
                    'label' => ' Subject password ',
                ]
            )
            ->textInput(['class' => 'has-text-right', 'tabindex' => '3']) ?>

        <?php $msgHelp = $data->subjectReconfirmationHelp() ?>
        <?= $field
            ->config($data, 'subjectReconfirmation')
            ->template("{label}<span><i class='fas fa-info-circle' title='$msgHelp'></i></span>{input}\n{error}")
            ->enclosedByContainer(true, ['class' => 'column is-half has-text-left'])
            ->label(
                true,
                [
                    'class' => 'has-text-black has-text-weight-bold',
                    'label' => ' Subject reconfirmation ',
                ]
            )
            ->textInput(['class' => 'has-text-left', 'tabindex' => '4']) ?>
    </div>

    <div class = 'columns'>
        <?php $msgHelp = $data->subjectRecoveryHelp() ?>
        <?= $field
            ->config($data, 'subjectRecovery')
            ->template("<span><i class='fas fa-info-circle' title='$msgHelp'></i></span>{label}{input}\n{error}")
            ->enclosedByContainer(true, ['class' => 'column is-half has-text-right'])
            ->label(
                true,
                [
                    'class' => 'has-text-black has-text-weight-bold',
                    'label' => ' Subject recovery ',
                ]
            )
            ->textInput(['class' => 'has-text-right', 'tabindex' => '5']) ?>

        <?php $msgHelp = $data->subjectWelcomeHelp() ?>
        <?= $field
            ->config($data, 'subjectWelcome')
            ->template("{label}<span><i class='fas fa-info-circle' title='$msgHelp'></i></span>{input}\n{error}")
            ->enclosedByContainer(true, ['class' => 'column is-half has-text-left'])
            ->label(
                true,
                [
                    'class' => 'has-text-black has-text-weight-bold',
                    'label' => ' Subject welcome ',
                ]
            )
            ->textInput(['class' => 'has-text-left', 'tabindex' => '6']) ?>
    </div>

        <div class = 'column is-4 is-offset-4'>
            <div class = 'flex items-center justify-between'>
                <?= Html::submitButton(
                    'Save ' . html::tag('i', '', ['class' => 'fas fa-sign-in-alt', 'aria-hidden' => 'true']),
                    [
                        'class' => 'button is-block is-info is-fullwidth',
                        'id' => 'settings-button',
                        'tabindex' => '4'
                    ]
                ) ?>
            </div>
        </div>

    <?= Form::end() ?>

</div>
