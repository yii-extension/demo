<?php

declare(strict_types=1);

/** @var string $content */

$this->beginPage();
    $this->beginBody();
        echo $content;
    $this->endBody();
$this->endPage();
