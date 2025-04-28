#!/usr/bin/env php
<?php

declare(strict_types=1);

use RWatch\App\App;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/bootstrap.php';

new App()->run();
