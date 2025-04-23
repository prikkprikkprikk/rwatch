<?php

declare(strict_types=1);

use RWatch\Config\ConfigFile;
use RWatch\Config\ConfigFilePath;

it('can read a ConfigFilePath', function () {
    $configFilePath = new ConfigFilePath(getDefaultConfigFilePath());
    $configFile = new ConfigFile($configFilePath);
    expect(json_decode($configFile->getContents(), true))->toBe([
        'server' => 'testServer',
        'username' => 'testUsername',
        'project' => 'testProject',
    ]);
});
