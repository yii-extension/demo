<?php

declare(strict_types=1);

/** @var array $content */
?>

<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns = 'http://www.w3.org/1999/xhtml'>
    <head>
        <meta name = 'viewport' content = 'width=device-width'>
        <meta http-equiv = 'Content-Type' content = 'text/html;charset=UTF-8'>
        <?php $this->head() ?>
    </head>
    <body class = 'mailer-html-body'>
        <?php $this->beginBody() ?>
            <?= $content ?>
        <?php $this->endBody() ?>

        <p class = 'mailer-html-p-content'>
            © Web Application Basic <?= date('Y') ?>.
        </p>
    </body>
</html>
<?php $this->endPage();
