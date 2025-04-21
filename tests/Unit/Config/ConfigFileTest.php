<?php

declare(strict_types=1);

use RWatch\Config\ConfigFile;
use RWatch\Config\ConfigFilePath;
use RWatch\Container\Container;
use RWatch\Filesystem\Contracts\FilesystemInterface;
use RWatch\Filesystem\TestFilesystem;

beforeEach(function() {
    $this->testFilename = createEmptyTestConfigFile();
    Container::reset();
    Container::bind(FilesystemInterface::class, TestFilesystem::class);
    $this->filesystem = Container::singleton(FilesystemInterface::class);
});

afterEach(function() {
    deleteTestConfigFile($this->testFilename);
});

it('can read a ConfigFilePath', function () {
    $this->filesystem->setFileConfig(
        $this->testFilename,
        [
            'isDirectory' => false,
            'isFile' => true,
            'exists' => true,
            'isReadable' => true,
            'contents' => '[]'
        ]
    );
    $configFilePath = new ConfigFilePath($this->testFilename);
    $configFile = new ConfigFile($configFilePath);
    expect($configFile->getContents())->toBe(json_encode([]));
});
