<?php

declare(strict_types=1);

use RWatch\Command\AskForServerNameCommand;
use RWatch\Config\ConfigFilePath;

it('updates the config with the given server name', function () {

    $config = new RWatch\Config\Config();

    $command = new AskForServerNameCommand();
});
