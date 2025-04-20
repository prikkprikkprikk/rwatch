<?php

declare(strict_types=1);

use RWatch\Command\Contracts\CommandInterface;
use RWatch\Command\LoadConfigFileCommand;
use RWatch\Command\PauseCommand;
use RWatch\Config\ConfigFilePath;
use RWatch\IO\TestIO;

it('can load an existing and valid config file', function () {
    $testIo = new TestIO([]);

    $testConfig = [
        'server' => 'testServer',
        'project' => 'testProject',
    ];

    $testConfigFilePath = createTestConfigFile($testConfig);

    $command = new LoadConfigFileCommand($testConfigFilePath);

    $nextCommand = $command->execute($testIo);

    expect($nextCommand)->toBeInstanceOf(CommandInterface::class);
});

it('returns a pause command when the config file does not exist', function () {
    $testIo = new TestIO([]);
    $testConfigFilePath = new ConfigFilePath('non-existant-config.json');
    $command = new LoadConfigFileCommand($testConfigFilePath);
    $nextCommand = $command->execute($testIo);
    expect($nextCommand)->toBeInstanceOf(PauseCommand::class);
});