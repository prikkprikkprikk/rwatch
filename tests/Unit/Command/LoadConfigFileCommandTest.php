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


beforeEach(function (): void {
    Container::reset();
    Container::bind(FilesystemInterface::class, TestFilesystem::class);
    $this->filesystem = Container::singleton(FilesystemInterface::class);
});


it('can load an existing and valid config file', function (): void {
    $filesystem = Container::singleton(FilesystemInterface::class);
    $validFilePath = "~/valid-config.json";
    $filesystem->setFileConfig(
        $validFilePath,
        [
            'isDirectory' => false,
            'isFile' => true,
            'exists' => true,
            'isReadable' => true,
            'contents' => '{}',
        ]
    );
    $command = new LoadConfigFileCommand($validFilePath);
    $nextCommand = $command->execute();
    expect($nextCommand)->toBeInstanceOf(CommandInterface::class);
});

it('returns a pause command when the config file does not exist', function (): void {
    $command = new LoadConfigFileCommand(configFilePath: '~/non-existant-config.json');
    $nextCommand = $command->execute();
    expect($nextCommand)->toBeInstanceOf(PauseCommand::class);
});

it('creates a ConfigFilePath with the default config file if none is specified', function (): void {
    $command = new LoadConfigFileCommand();
    $filesystem = Container::singleton(FilesystemInterface::class);
    $filesystem->setFileConfig(
        getDefaultConfigFilePath(),
        [
            'isDirectory' => false,
            'isFile' => true,
            'exists' => true,
            'isReadable' => true,
            'contents' => '{}',
        ]
    );
    $nextCommand = $command->execute();
    expect($nextCommand)->toBeInstanceOf(HydrateAppStateCommand::class);
});