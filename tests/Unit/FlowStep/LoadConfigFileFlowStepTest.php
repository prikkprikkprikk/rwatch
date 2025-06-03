<?php

declare(strict_types=1);

use RWatch\AppFlow\Contracts\FlowStepInterface;
use RWatch\AppFlow\HydrateAppStateFlowStep;
use RWatch\AppFlow\LoadConfigFileFlowStep;
use RWatch\AppFlow\PauseFlowStep;
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
    $flowStep = new LoadConfigFileFlowStep($validFilePath);
    $nextFlowStep = $flowStep->execute();
    expect($nextFlowStep)->toBeInstanceOf(FlowStepInterface::class);
});

it('returns a pause command when the config file does not exist', function (): void {
    $flowStep = new LoadConfigFileFlowStep(configFilePath: '~/non-existant-config.json');
    $nextFlowStep = $flowStep->execute();
    expect($nextFlowStep)->toBeInstanceOf(PauseFlowStep::class);
});

it('creates a ConfigFilePath with the default config file if none is specified', function (): void {
    $flowStep = new LoadConfigFileFlowStep();
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
    $nextFlowStep = $flowStep->execute();
    expect($nextFlowStep)->toBeInstanceOf(HydrateAppStateFlowStep::class);
});
