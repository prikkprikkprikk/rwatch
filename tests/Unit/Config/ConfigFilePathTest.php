<?php

use RWatch\Config\ConfigFilePath;
use RWatch\Config\Exception\DirectoryNotFoundException;
use RWatch\Config\Exception\FileNotFoundException;
use RWatch\Config\Exception\WrongFileFormatException;
use RWatch\Filesystem\TestFilesystem;

beforeEach(function() {
    $this->tempDir = createTempDir();
    $this->testFilename = createEmptyTestConfigFile();
});

afterEach(function() {
    deleteTestConfigFile($this->testFilename);
});

it('is created with a default directory and filename if no arguments are supplied and the file is valid', function() {
    $testFilesystem = new TestFilesystem();
    $defaultDir = getenv('HOME') . str_replace('~', '', ConfigFilePath::DEFAULT_DIRECTORY);
    // The default file is in home directory + .config/rwatch/config.json
    $defaultFile = $defaultDir . "/" . ConfigFilePath::DEFAULT_FILENAME;
    $testFilesystem->setFileConfig($defaultFile, [
        'isDirectory' => false,
        'isFile' => true,
        'exists' => true,
    ]);
    $config = new ConfigFilePath( filesystem: $testFilesystem );
    expect($config)->toBeInstanceOf(ConfigFilePath::class);
    expect($config->directory)->toBe($defaultDir);
    expect($config->filename)->toBe(ConfigFilePath::DEFAULT_FILENAME);
});

it('can be constructed with directory and filename', function() {
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
    $config = new ConfigFilePath($fullPath);
    expect($config)->toBeInstanceOf(ConfigFilePath::class);
});

it('returns correct full path', function() {
    $config = new ConfigFilePath($this->tempDir, 'config.json');
    expect($config->fullPath())->toBe($this->tempDir . '/config.json');
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
    $config = new ConfigFilePath($this->tempDir . '//config.json');
    expect($config->fullPath())->toBe($this->tempDir . '/config.json');
});

it('accepts home-relative paths', function() {
    file_put_contents(getenv('HOME') . '/temp_config.json', '{}');
    $config = new ConfigFilePath('~/temp_config.json');
    expect($config->fullPath())
        ->toBe(getenv('HOME') . '/temp_config.json');

    unlink(getenv('HOME') . '/temp_config.json');
});

it('throws exception when directory does not exist', function() {
    expect(fn() => new ConfigFilePath('/not/a/file'))
        ->toThrow(DirectoryNotFoundException::class,
        'Directory does not exist: /not/a/file'
        );
});

it('throws exception when path is not a JSON file', function() {

    // Arrange ---------------------------------------------------------------------------------
    file_put_contents($this->tempDir . '/config.txt', '{}');

    // Act ------------------------------------------------------------------------------------

    // Assert ---------------------------------------------------------------------------------
    expect(fn() => new ConfigFilePath($this->tempDir . '/config.txt'))
        ->toThrow(WrongFileFormatException::class, 'Config file is not a JSON file');

    // Cleanup --------------------------------------------------------------------------------
    unlink($this->tempDir . '/config.txt');
});

it('correctly determines whether the file exists', function() {

    expect(fn() => new ConfigFilePath($this->tempDir . '/non-existent-file.json', 'config.json'))
        ->toThrow(FileNotFoundException::class);
});

it('creates a default config file path', function () {
    $testFilesystem = new TestFilesystem();
    $defaultDir = getenv('HOME') . str_replace('~', '', ConfigFilePath::DEFAULT_DIRECTORY);
    // The default file is in home directory + .config/rwatch/config.json
    $defaultFile = $defaultDir . "/" . ConfigFilePath::DEFAULT_FILENAME;
    $testFilesystem->setFileConfig($defaultFile, [
        'isDirectory' => false,
        'isFile' => true,
        'exists' => true,
    ]);
    $config = ConfigFilePath::getDefaultConfigFilePath(filesystem: $testFilesystem);

    // Assert ---------------------------------------------------------------------------------
    expect($config->directory)->toBe($defaultDir);
    expect($config->filename)->toBe(ConfigFilePath::DEFAULT_FILENAME);
    expect($config->fullPath())->toBe($defaultFile);
});
