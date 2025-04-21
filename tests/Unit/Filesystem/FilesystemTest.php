<?php

declare(strict_types=1);

use RWatch\Filesystem\Filesystem;

beforeEach(function () {
    $this->filesystem = new Filesystem();
    $this->tempDir = createTempDir();
    $this->testFilename = createEmptyTestConfigFile();
});

afterEach(function() {
    deleteTestConfigFile($this->testFilename);
});

it('can identify a directory as a directory', function () {
    expect($this->filesystem->isDirectory($this->tempDir))->toBeTrue();
});

it('does not identify a file as a directory', function () {
    expect($this->filesystem->isDirectory($this->testFilename))->toBeFalse();
});

it('can identify a file as a file', function () {
    expect($this->filesystem->isFile($this->testFilename))->toBeTrue();
});

it('does not identify a directory as a file', function () {
    expect($this->filesystem->isFile($this->tempDir))->toBeFalse();
});

it('does not identify a non-existent file as a file', function () {
    expect($this->filesystem->isFile($this->tempDir . "/non-existent-file.txt"))->toBeFalse();
});

it('can get the directory of a full path', function () {
    expect($this->filesystem->getDirectory($this->testFilename))->toBe($this->tempDir);
});

it('can determine if a file exists', function () {
    expect($this->filesystem->exists($this->testFilename))->toBeTrue();
});

it('can determine if a file does not exist', function () {
    expect($this->filesystem->exists($this->tempDir . "/non-existent-file.txt"))->toBeFalse();
});

it('can join paths', function () {
    expect($this->filesystem->join($this->tempDir, 'test.txt'))->toBe($this->tempDir . '/test.txt');
});
