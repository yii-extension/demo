<?php

declare(strict_types=1);

use App\Module\User\Asset\AdminInfoAsset;
use Yiisoft\Assets\AssetManager;
use Yiisoft\Html\Html;

/**
 * @var AssetManager $assetManager
 */
$assetManager->register([
    AdminInfoAsset::class
]);

$this->setTitle('Information details.');

?>

<section class = "section">

    <div class = "container">

        <p class='title has-text-black'>
            <b>Information details.</b>
        </p>

        <div class = "columns is-variable bd-klmn-columns is-2 is-multiline is-vcentered">

            <div class = "column is-3">

                <div class='has-text-right has-text-black'>
                    <b>Username:</b>
                </div>

            </div>

            <div class = "column is-9">

                <div class='notification is-info has-text-left'>
                    <?= $data->username ?>
                </div>

            </div>

            <div class = "column is-3">

                <div class='has-text-right has-text-black'>
                    <b>Email:</b>
                </div>

            </div>

            <div class = "column is-9">
                <div class='notification is-info has-text-left'>
                    <?= $data->email ?>
                </div>

            </div>

            <div class = "column is-3">

                <div class='has-text-right has-text-black'>
                    <b>Registration time:</b>
                </div>

            </div>

            <div class = "column is-9">

                <div class='notification is-info has-text-left'>
                    <?= date('Y-m-d H:i:s', $data->created_at) ?>
                </div>

            </div>

            <div class = "column is-3">

                <div class='has-text-right has-text-black'>
                    <b>Registration IP:</b>
                </div>

            </div>

            <div class = "column is-9">

                <?php if ($data->registration_ip !== null) : ?>
                    <div class='notification is-info has-text-left'>
                        <?= $data->registration_ip ?>
                    </div>
                <?php endif ?>

            </div>

            <div class = "column is-3">

                <div class='has-text-right has-text-black'>
                    <b>Confirmation status:</b>
                </div>

            </div>

            <div class = "column is-9">

                <?php if ($data->isConfirmed()) : ?>
                    <div class='notification is-success has-text-left'>
                        <?=  date('Y-m-d H:i:s', $data->confirmed_at) ?>
                    </div>
                <?php else : ?>
                    <div class='notification is-danger'>
                        Unconfirmed
                    </div>
                <?php endif ?>

            </div>

            <div class = "column is-3">

                <div class='has-text-right has-text-black'>
                    <b>Block status:</b>
                </div>

            </div>

            <div class = "column is-9">

                <?php if ($data->isBlocked()) : ?>
                    <div class='notification is-danger has-text-left'>
                        <?= 'Blocked at: ', date('Y-m-d H:i:s', $model->blocked_at) ?>
                    </div>
                <?php else : ?>
                    <div class='notification is-success has-text-left'>
                        Not blocked
                    </div>
                <?php endif ?>

            </div>

        </div>

    </div>

</section>
