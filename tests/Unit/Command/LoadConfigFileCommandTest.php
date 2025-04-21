<?php

declare(strict_types=1);

use RWatch\Command\Contracts\CommandInterface;
use RWatch\Command\HydrateAppStateCommand;
use RWatch\Command\LoadConfigFileCommand;
use RWatch\Command\PauseCommand;
use RWatch\Config\ConfigFilePath;
use RWatch\Container\Container;
use RWatch\Filesystem\Contracts\FilesystemInterface;
use RWatch\Filesystem\TestFilesystem;
use RWatch\IO\TestIO;


beforeEach(function () {
    Container::reset();
    Container::bind(FilesystemInterface::class, TestFilesystem::class);
    $this->filesystem = Container::singleton(FilesystemInterface::class);
});


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

    $defaultDirectory = getenv('HOME') . str_replace('~', '', ConfigFilePath::DEFAULT_DIRECTORY);
    $this->filesystem->setFileConfig(
        $defaultDirectory . "/non-existant-config.json",
        [
            'isDirectory' => false,
            'isFile' => false,
            'exists' => false,
        ]
    );
    $command = new LoadConfigFileCommand(configFilePath: '~/non-existant-config.json');
    $nextCommand = $command->execute($testIo);
    expect($nextCommand)->toBeInstanceOf(PauseCommand::class);
});

it('creates a ConfigFilePath with the default config file if none is specified', function () {
    $testIo = new TestIO([]);
    $fullDefaultPath = getenv('HOME') . str_replace('~', '', ConfigFilePath::DEFAULT_DIRECTORY) . "/" . ConfigFilePath::DEFAULT_FILENAME;
    $this->filesystem->setFileConfig(
        $fullDefaultPath,
        [
            'isDirectory' => false,
            'isFile' => true,
            'exists' => true,
            'isReadable' => true,
            'contents' => '{}',
        ]
    );
    $command = new LoadConfigFileCommand();
    $nextCommand = $command->execute($testIo);
    expect($nextCommand)->toBeInstanceOf(HydrateAppStateCommand::class);
});