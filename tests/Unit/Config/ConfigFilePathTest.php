<?php

use RWatch\Config\ConfigFilePath;
use RWatch\Config\Exception\DirectoryNotFoundException;
use RWatch\Config\Exception\FileNotFoundException;
use RWatch\Config\Exception\WrongFileFormatException;
use RWatch\Container\Container;
use RWatch\Filesystem\Contracts\FilesystemInterface;
use RWatch\Filesystem\TestFilesystem;

beforeEach(function() {
    Container::reset();
    Container::bind(FilesystemInterface::class, TestFilesystem::class);
    $this->filesystem = Container::singleton(FilesystemInterface::class);
    $this->tempDir = createTempDir();
    $this->testFilename = createEmptyTestConfigFile();
    $this->filesystem->setFileConfig(
        $this->testFilename,
        [
            'isDirectory' => false,
            'isFile' => true,
            'exists' => true,
        ]
    );
});

afterEach(function() {
    deleteTestConfigFile($this->testFilename);
});

it('is created with a default directory and filename if no arguments are supplied and the file is valid', function() {
    $defaultDir = getenv('HOME') . str_replace('~', '', ConfigFilePath::DEFAULT_DIRECTORY);
    // The default file is in home directory + .config/rwatch/config.json
    $defaultFile = $defaultDir . "/" . ConfigFilePath::DEFAULT_FILENAME;
    $this->filesystem->setFileConfig($defaultFile, [
        'isDirectory' => false,
        'isFile' => true,
        'exists' => true,
    ]);
    $config = new ConfigFilePath();
    expect($config)->toBeInstanceOf(ConfigFilePath::class);
    expect($config->directory)->toBe($defaultDir);
    expect($config->filename)->toBe(ConfigFilePath::DEFAULT_FILENAME);
});

it('can be constructed with directory and filename', function() {
    $this->filesystem->setFileConfig(
        $this->tempDir . '/config.json',
        [
            'isDirectory' => false,
            'isFile' => true,
            'exists' => true,
        ]
    );
    $config = new ConfigFilePath($this->tempDir, 'config.json');
    expect($config)->toBeInstanceOf(ConfigFilePath::class);
});

it("can determine that the file doesn't exist", function() {
    expect(function() {
        $config = new ConfigFilePath($this->tempDir, 'non-existant-config.json');
    })->toThrow(FileNotFoundException::class);
});

it('can be constructed with full path', function() {
    $fullPath = $this->tempDir . '/config.json';
    $this->filesystem->setFileConfig(
        $fullPath,
        [
            'isDirectory' => false,
            'isFile' => true,
            'exists' => true,
        ]
    );
    $config = new ConfigFilePath($fullPath);
    expect($config)->toBeInstanceOf(ConfigFilePath::class);
});

it('returns correct full path', function() {
    $fullPath = $this->tempDir . '/config.json';
    $config = new ConfigFilePath($this->tempDir, 'config.json');
    expect($config->fullPath())->toBe($fullPath);
});

it('returns correct directory', function() {
    $config = new ConfigFilePath($this->tempDir, 'config.json');
    expect($config->directory)->toBe($this->tempDir);
});

it('returns correct filename', function() {
    $config = new ConfigFilePath($this->tempDir, 'config.json');
    expect($config->filename)->toBe('config.json');
});

it('normalizes path with double slashes', function() {
    $config = new ConfigFilePath($this->tempDir . '/config.json');
    expect($config->fullPath())->toBe($this->tempDir . '/config.json');
});

it('accepts home-relative paths', function() {
    $this->filesystem->setFileConfig(
        getenv('HOME') . '/temp_config.json',
        [
            'isDirectory' => false,
            'isFile' => true,
            'exists' => true,
        ]
    );
    $config = new ConfigFilePath('~/temp_config.json');
    expect($config->fullPath())
        ->toBe(getenv('HOME') . '/temp_config.json');
});

it('throws exception when directory does not exist', function() {
    $notAFile = $this->tempDir . '/not-a-file';
    $this->filesystem->setFileConfig(
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

it('throws exception when path is not a JSON file', function() {
    $notAJsonFile = $this->tempDir . '/not-a-json-file.txt';
    $this->filesystem->setFileConfig(
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

it('correctly determines whether the file exists', function() {
    expect(fn() => new ConfigFilePath($this->tempDir . '/non-existent-file.json', 'config.json'))
        ->toThrow(FileNotFoundException::class);
});

it('creates a default config file path', function () {
    $defaultDir = getenv('HOME') . str_replace('~', '', ConfigFilePath::DEFAULT_DIRECTORY);
    // The default file is in home directory + /.config/rwatch/config.json
    $defaultFile = $defaultDir . "/" . ConfigFilePath::DEFAULT_FILENAME;
    $this->filesystem->setFileConfig(
        $defaultFile,
        [
            'isDirectory' => false,
            'isFile' => true,
            'exists' => true,
        ]
    );
    $config = ConfigFilePath::getDefaultConfigFilePath();

    // Assert ---------------------------------------------------------------------------------
    expect($config->directory)->toBe($defaultDir);
    expect($config->filename)->toBe(ConfigFilePath::DEFAULT_FILENAME);
    expect($config->fullPath())->toBe($defaultFile);
});
