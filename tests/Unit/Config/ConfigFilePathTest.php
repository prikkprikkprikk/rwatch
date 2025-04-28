<?php

use RWatch\Config\ConfigFilePath;
use RWatch\Config\Exception\DirectoryNotFoundException;
use RWatch\Config\Exception\FileNotFoundException;
use RWatch\Config\Exception\WrongFileFormatException;
use RWatch\Container\Container;
use RWatch\Filesystem\Contracts\FilesystemInterface;

it('is created with a default directory and filename if no arguments are supplied and the file is valid', function(): void {
    $configFilePath = new ConfigFilePath();
    $defaultDir = getenv('HOME') . str_replace('~', '', ConfigFilePath::DEFAULT_DIRECTORY);
    expect($configFilePath)->toBeInstanceOf(ConfigFilePath::class);
    expect($configFilePath->directory)->toBe($defaultDir);
    expect($configFilePath->filename)->toBe(ConfigFilePath::DEFAULT_FILENAME);
});

it('can be constructed with directory and filename', function(): void {
    $filesystem = Container::singleton(FilesystemInterface::class);
    $configFilePath = new ConfigFilePath(getDefaultConfigDirectory(), 'config.json');
    expect($configFilePath)->toBeInstanceOf(ConfigFilePath::class);
});

it("can determine that the file doesn't exist", function(): void {
    expect(function(): void {
        new ConfigFilePath(getDefaultConfigDirectory(), 'non-existant-config.json');
    })->toThrow(FileNotFoundException::class);
});

it('can be constructed with full path', function(): void {
    $filesystem = Container::singleton(FilesystemInterface::class);
    $fullPath = getDefaultConfigDirectory() . '/custom_config.json';
    $filesystem->setFileConfig(
        $fullPath,
        [
            'isDirectory' => false,
            'isFile' => true,
            'exists' => true,
        ]
    );
    $configFilePath = new ConfigFilePath($fullPath);
    expect($configFilePath)->toBeInstanceOf(ConfigFilePath::class);
});

it('returns correct full path', function(): void {
    $fullPath = getDefaultConfigFilePath();
    $configFilePath = new ConfigFilePath(getDefaultConfigDirectory(), 'config.json');
    echo "In test. configFilePath->fullPath():" . $configFilePath->fullPath() . PHP_EOL;
    expect($configFilePath->fullPath())->toBe($fullPath);
});

it('returns correct directory', function(): void {
    $configFilePath = new ConfigFilePath(getDefaultConfigDirectory(), 'config.json');
    expect($configFilePath->directory)->toBe(getDefaultConfigDirectory());
});

it('returns correct filename', function(): void {
    $configFilePath = new ConfigFilePath(getDefaultConfigDirectory(), 'config.json');
    expect($configFilePath->filename)->toBe('config.json');
});

it('normalizes path with double slashes', function(): void {
    $configFilePath = new ConfigFilePath(getDefaultConfigDirectory() . '/config.json');
    expect($configFilePath->fullPath())->toBe(getDefaultConfigDirectory() . '/config.json');
});

it('accepts home-relative paths', function(): void {
    $filesystem = Container::singleton(FilesystemInterface::class);
    $someOtherFullFilePath = getenv('HOME') . '/temp_config.json';
    $filesystem->setFileConfig(
        $someOtherFullFilePath,
        [
            'isDirectory' => false,
            'isFile' => true,
            'exists' => true,
        ]
    );
    $configFilePath = new ConfigFilePath('~/temp_config.json');
    expect($configFilePath->fullPath())
        ->toBe($someOtherFullFilePath);
});

it('throws exception when directory does not exist', function(): void {
    $filesystem = Container::singleton(FilesystemInterface::class);
    $notAFile = getDefaultConfigDirectory() . '/not-a-file';
    $filesystem->setFileConfig(
        $notAFile,
        [
            'isDirectory' => false,
            'isFile' => false,
            'exists' => false,
        ]
    );
    expect(fn() => new ConfigFilePath($notAFile))
        ->toThrow(DirectoryNotFoundException::class,
        "Directory does not exist: $notAFile"
        );
});

it('throws exception when path is not a JSON file', function(): void {
    $filesystem = Container::singleton(FilesystemInterface::class);
    $notAJsonFile = getDefaultConfigDirectory() . '/not-a-json-file.txt';
    $filesystem->setFileConfig(
        $notAJsonFile,
        [
            'isDirectory' => false,
            'isFile' => true,
            'exists' => true,
        ]
    );
    expect(fn() => new ConfigFilePath($notAJsonFile))
        ->toThrow(WrongFileFormatException::class, 'Config file is not a JSON file');
});

it('correctly determines whether the file exists', function(): void {
    expect(fn() => new ConfigFilePath(getDefaultConfigDirectory() . '/non-existent-file.json', 'config.json'))
        ->toThrow(FileNotFoundException::class);
});

it('creates a default config file path', function (): void {
    $configFilePath = ConfigFilePath::getDefaultConfigFilePath();

    // Assert ---------------------------------------------------------------------------------
    expect($configFilePath->directory)->toBe(getDefaultConfigDirectory());
    expect($configFilePath->filename)->toBe(ConfigFilePath::DEFAULT_FILENAME);
    expect($configFilePath->fullPath())->toBe(getDefaultConfigFilePath());
});
