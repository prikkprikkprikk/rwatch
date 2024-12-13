<?php

use Dwatch\Config\ConfigFilePath;
use Dwatch\Config\Exception\WrongFileFormatException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

beforeEach(function() {
    $this->filesystem = new Filesystem();
    // Create temporary test files/directories if needed
    $this->tempDir = sys_get_temp_dir() . '/configtest';
    if (!file_exists($this->tempDir)) {
        $this->filesystem->mkdir($this->tempDir, 0755);
    }
    $this->tempDir = realpath($this->tempDir);
    $this->filesystem->touch($this->tempDir . '/config.json');
    $this->filesystem->appendToFile($this->tempDir . '/config.json', '{}');
});

afterEach(function() {
    // Cleanup
    if (file_exists($this->tempDir . '/config.json')) {
        unlink($this->tempDir . '/config.json');
    }
    if (file_exists($this->tempDir)) {
        rmdir($this->tempDir);
    }
});

it('can be constructed with directory and filename', function() {
    $config = new ConfigFilePath($this->tempDir, 'config.json');
    expect($config)->toBeInstanceOf(ConfigFilePath::class);
});

it("can determine that the file doesn't exist", function() {
    $config = new ConfigFilePath($this->tempDir, 'non-existant-config.json');
    expect($config->fileExists())->toBeFalse();
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
    expect($config->directory())->toBe($this->tempDir);
});

it('returns correct filename', function() {
    $config = new ConfigFilePath($this->tempDir, 'config.json');
    expect($config->filename())->toBe('config.json');
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

it('throws exception when path is not a file', function() {
    expect(fn() => new ConfigFilePath('/not/a/file'))
        ->toThrow(WrongFileFormatException::class, 'Config file is not a JSON file');
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

it('correctly determines whether the directory exists', function() {

    // Arrange ---------------------------------------------------------------------------------
    $configWithExistingDirectory = new ConfigFilePath($this->tempDir, 'config.json');
    $configWithoutExistingDirectory = new ConfigFilePath($this->tempDir . '/non-existent-directory', 'config.json');

    // Act ------------------------------------------------------------------------------------

    // Assert ---------------------------------------------------------------------------------
    expect($configWithExistingDirectory->directoryExists())->toBeTrue();
    expect($configWithoutExistingDirectory->directoryExists())->toBeFalse();
});

it('correctly determines whether the directory is writable', function() {

    // Arrange ---------------------------------------------------------------------------------
    $configWithWritableDirectory = new ConfigFilePath($this->tempDir, 'config.json');
    $configWithUnwritableDirectory = new ConfigFilePath('/etc', 'config.json');

    // Act ------------------------------------------------------------------------------------

    // Assert ---------------------------------------------------------------------------------
    expect($configWithWritableDirectory->directoryIsWritable())->toBeTrue();
    expect($configWithUnwritableDirectory->directoryIsWritable())->toBeFalse();
});

it('correctly determines whether the file exists', function() {

    // Arrange ---------------------------------------------------------------------------------
    $configWithExistingFile = new ConfigFilePath($this->tempDir, 'config.json');
    $configWithoutExistingFile = new ConfigFilePath($this->tempDir . '/non-existent-file.json', 'config.json');

    // Act ------------------------------------------------------------------------------------

    // Assert ---------------------------------------------------------------------------------
    expect($configWithExistingFile->fileExists())->toBeTrue();
    expect($configWithoutExistingFile->fileExists())->toBeFalse();
});

it('creates a default config file path', function () {
    // Arrange ---------------------------------------------------------------------------------

    // Act ------------------------------------------------------------------------------------
    $config = ConfigFilePath::getDefaultConfigFilePath();

    // Assert ---------------------------------------------------------------------------------
    expect($config->directory())->toBe(getenv('HOME') . str_replace('~', '', ConfigFilePath::DEFAULT_DIRECTORY));
    expect($config->filename())->toBe(ConfigFilePath::DEFAULT_FILENAME);
});
