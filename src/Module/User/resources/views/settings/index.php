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
    Module user config.
</p>

<hr class='mb-2'/>

<?= Form::widget()
    ->action($action)
    ->options(
        [
            'id' => 'form-settings',
            'class' => 'forms-settings bg-white shadow-md rounded',
            'csrf' => $csrf
        ]
    )
    ->begin() ?>

    <div class = 'columns'>
        <?php $msgHelp = $data->registerHelp() ?>
        <?= $field->config($data, 'register')
            ->enclosedByContainer(true, ['class' => 'column is-half has-text-right'])
            ->template("<span><i class='fas fa-info-circle' title='$msgHelp'></i></span>{input}{label}")
            ->label(
                true,
                [
                    'class' => 'has-text-black has-text-weight-bold',
                    'label' => ' Register ',
                    'for' => 'switchRegister'
                ]
            )
            ->checkbox(
                [
                    'id' => 'switchRegister',
                    'class' => 'switch is-outlined is-rtl',
                    'tabindex' => '1'
                ],
                false
            ) ?>

        <?php $msgHelp = $data->confirmationHelp() ?>
        <?= $field->config($data, 'confirmation')
            ->enclosedByContainer(true, ['class' => 'column is-half has-text-left'])
            ->template("{input}{label}<span><i class='fas fa-info-circle' title='$msgHelp'></i></span>")
            ->label(
                true,
                [
                    'class' => 'has-text-black has-text-weight-bold',
                    'label' => ' Confirmation ',
                    'for' => 'switchConfirm'
                ]
            )
            ->checkbox(
                [
                    'id' => 'switchConfirm',
                    'class' => 'switch is-outlined',
                    'tabindex' => '2'
                ],
                false
            ) ?>
    </div>

    <div class = 'columns'>
        <?php $msgHelp = $data->passwordRecoveryHelp() ?>
        <?= $field->config($data, 'passwordRecovery')
            ->enclosedByContainer(true, ['class' => 'column is-half has-text-right'])
            ->template("<span><i class='fas fa-info-circle' title='$msgHelp'></i></span>{input}{label}")
            ->label(
                true,
                [
                    'class' => 'has-text-black has-text-weight-bold',
                    'label' => ' Password recovery ',
                    'for' => 'switchPasswordRecovery'
                ]
            )
            ->checkbox(
                [
                    'id' => 'switchPasswordRecovery',
                    'class' => 'switch is-outlined is-rtl',
                    'tabindex' => '3'
                ],
                false
            ) ?>

        <?php $msgHelp = $data->generatingPasswordHelp() ?>
        <?= $field->config($data, 'generatingPassword')
            ->enclosedByContainer(true, ['class' => 'column is-half has-text-left'])
            ->template("{input}{label}<span><i class='fas fa-info-circle' title='$msgHelp'></i></span>")
            ->label(
                true,
                [
                    'class' => 'has-text-black has-text-weight-bold',
                    'label' => ' Generating password ',
                    'for' => 'switchGeneratingPassword'
                ]
            )
            ->checkbox(
                [
                    'id' => 'switchGeneratingPassword',
                    'class' => 'switch is-outlined',
                    'tabindex' => '4'
                ],
                false
            ) ?>
    </div>

    <div class = 'columns'>
        <?php $msgHelp = $data->userNameCaseSensitiveHelp() ?>
        <?= $field->config($data, 'userNameCaseSensitive')
            ->enclosedByContainer(true, ['class' => 'column is-half has-text-right'])
            ->template("<span><i class='fas fa-info-circle' title='$msgHelp'></i></span>{input}{label}")
            ->label(
                true,
                [
                    'class' => 'has-text-black has-text-weight-bold',
                    'label' => ' Case sensitive ',
                    'for' => 'switchUserNameCaseSensitive'
                ]
            )
            ->checkbox(
                [
                    'id' => 'switchUserNameCaseSensitive',
                    'class' => 'switch is-outlined is-rtl',
                    'tabindex' => '5'
                ],
                false
            ) ?>

        <?php $msgHelp = '' ?>
        <?= $field->config($data, 'delete')
            ->enclosedByContainer(true, ['class' => 'column is-half has-text-left'])
            ->template("{input}{label}<span><i class='fas fa-info-circle' title='$msgHelp'></i></span>")
            ->label(
                true,
                [
                    'class' => 'has-text-black has-text-weight-bold',
                    'label' => ' Account delete ',
                    'for' => 'switchDelete'
                ]
            )
            ->checkbox(
                [
                    'id' => 'switchDelete',
                    'class' => 'switch is-outlined',
                    'tabindex' => '6'
                ],
                false
            ) ?>
    </div>

    <div class = 'columns'>
        <?php $msgHelp = $data->messageHeaderHelp() ?>
        <?= $field->config($data, 'messageHeader')
            ->enclosedByContainer(true, ['class' => 'column is-half has-text-right'])
            ->template("<span><i class='fas fa-info-circle' title='$msgHelp'></i></span>{label}{input}\n{error}")
            ->label(
                true,
                [
                    'class' => 'has-text-black has-text-weight-bold',
                    'label' => ' Message header '
                ]
            )
            ->textInput(['class' => 'is-vbaseline has-text-right', 'tabindex' => '7']) ?>

        <?php $msgHelp = $data->userNameRegExpHelp() ?>
        <?= $field->config($data, 'userNameRegExp')
            ->enclosedByContainer(true, ['class' => 'column is-half has-text-left'])
            ->template("{label}<span><i class='fas fa-info-circle' title='$msgHelp'></i></span>{input}\n{error}")
            ->label(
                true,
                [
                    'class' => 'has-text-black has-text-weight-bold',
                    'label' => ' Username Reg. Exp. '
                ]
            )
            ->textInput(['class' => 'is-vbaseline', 'tabindex' => '8']) ?>
    </div>

    <div class = 'columns'>
        <?php $msgHelp = $data->tokenConfirmWithinHelp() ?>
        <?= $field->config($data, 'tokenConfirmWithin')
            ->enclosedByContainer(true, ['class' => 'column is-half has-text-right'])
            ->template("<span><i class='fas fa-info-circle' title='$msgHelp'></i></span>{label}{input}\n{error}")
            ->label(
                true,
                [
                    'class' => 'has-text-black has-text-weight-bold',
                    'label' => ' Token Confirm Within ',
                ]
            )
            ->textInput(['class' => 'is-vbaseline has-width-100 has-text-right', 'tabindex' => '9']) ?>

        <?php $msgHelp = $data->tokenRecoverWithinHelp() ?>
        <?= $field->config($data, 'tokenRecoverWithin')
            ->enclosedByContainer(true, ['class' => 'column is-half has-text-left'])
            ->template("{input}{label}<span><i class='fas fa-info-circle' title='$msgHelp'></i></span>\n{error}")
            ->label(
                true,
                [
                    'class' => 'has-text-black has-text-weight-bold',
                    'label' => ' Token Recover Within ',
                ]
            )
            ->textInput(['class' => 'is-vbaseline has-width-100', 'tabindex' => '10']) ?>
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
