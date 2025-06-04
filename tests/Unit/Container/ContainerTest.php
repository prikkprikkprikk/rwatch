<?php

declare(strict_types=1);

use RWatch\App\AppState;
use RWatch\App\Contracts\AppStateInterface;
use RWatch\CommandLineOptions\CommandLineOptions;
use RWatch\CommandLineOptions\CommandLineOptionsInterface;
use RWatch\Config\Config;
use RWatch\Config\ConfigInterface;
use RWatch\Container\Container;
use RWatch\Filesystem\Contracts\FilesystemInterface;
use RWatch\Filesystem\Filesystem;
use RWatch\Filesystem\TestFilesystem;
use RWatch\IO\ConsoleIO;
use RWatch\IO\IOInterface;

// Import helper functions from Pest.php
use function getDefaultConfigFilePath;


beforeEach(function (): void {
    Container::reset();
    Container::bind(
        CommandLineOptionsInterface::class,
        CommandLineOptions::class,
    );
    Container::bind(
        ConfigInterface::class,
        Config::class,
    );
    Container::bind(
        FilesystemInterface::class,
        TestFilesystem::class,
    );
    Container::bind(
        IOInterface::class,
        ConsoleIO::class,
    );
    Container::bind(
        AppStateInterface::class,
        AppState::class,
    );

    // Set up default config file response
    $filesystem = Container::singleton(FilesystemInterface::class);
    $filesystem->setFileConfig(
        getDefaultConfigFilePath(),
        [
            'isDirectory' => false,
            'isFile' => true,
            'exists' => true,
            'isReadable' => true,
            'contents' => '{
                "server": "testServer",
                "username": "testUsername",
                "project": "testProject"
            }'
        ]
    );
});

it('can instantiate and return a CommandLineOptions singleton', function (): void {
    $options = Container::singleton(CommandLineOptionsInterface::class);
    expect($options)->toBeInstanceOf(CommandLineOptions::class);
});

it('can instantiate and return a Config singleton', function (): void {
    $config = Container::singleton(ConfigInterface::class);
    expect($config)->toBeInstanceOf(Config::class);
});

it('can instantiate and return a Filesystem singleton', function (): void {
    $filesystem = Container::singleton(FilesystemInterface::class);
    expect($filesystem)->toBeInstanceOf(Filesystem::class);
});

it('can instantiate and return a ConsoleIO singleton', function (): void {
    $io = Container::singleton(IOInterface::class);
    expect($io)->toBeInstanceOf(ConsoleIO::class);
});

it('can instantiate and return a AppState singleton', function (): void {
    $appState = Container::singleton(AppStateInterface::class);
    expect($appState)->toBeInstanceOf(AppState::class);
});

it('can replace the Filesystem and return a TestFilesystem singleton', function (): void {

    Container::bind(
        FilesystemInterface::class,
        TestFilesystem::class,
    );

    $filesystem = Container::singleton(FilesystemInterface::class);

    expect($filesystem)->toBeInstanceOf(TestFilesystem::class);
});

it('can contain a concrete instance of a class', function (): void {
    $filesystem = new TestFilesystem();
    Container::bind(
        FilesystemInterface::class,
        $filesystem,
    );

    $filesystem2 = Container::singleton(FilesystemInterface::class);
    expect($filesystem2)->toBe($filesystem);
});

it('throws an exception if the class does not exist', function (): void {
    expect(function (): void {
        Container::bind(
            'NonExistantClass',
            'NonExistantClass',
        );
    })->toThrow(InvalidArgumentException::class);
});

it('throws an exception if the supplied object is not an instance of the class', function (): void {
    $filesystem = new TestFilesystem();
    expect(fn () => Container::bind(
            IOInterface::class,
            $filesystem,
        )
    )->toThrow(InvalidArgumentException::class);
});
