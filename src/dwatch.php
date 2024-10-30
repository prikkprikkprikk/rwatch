#!/usr/bin/env php
<?php
declare(strict_types=1);

use Dwatch\App\App;

require __DIR__ . '/../vendor/autoload.php';

$app = new App();
$app->run();
