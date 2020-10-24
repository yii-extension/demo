<?php

declare(strict_types=1);

/** @var array $params */
?>
Hello,

'Your account has a new password.',

We have generated a password for you:

<?= '<b>' . $params['password'] . '</b>' ?>

If you did not make this request you can ignore this email.
