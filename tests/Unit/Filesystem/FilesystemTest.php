<?php

declare(strict_types=1);

use RWatch\Container\Container;

it('can identify a directory as a directory and not as a file', function (): void {
    $filesystem = Container::singleton(\RWatch\Filesystem\Contracts\FilesystemInterface::class);
    $path = '/tmp';
    $filesystem->setFileConfig(
        $path,
        [
            "isDirectory" => true,
            "isFile" => false,
            "exists" => true,
        ]
    );
    expect($filesystem->isDirectory($path))->toBeTrue()
        ->and($filesystem->isFile($path))->toBeFalse();
});

it('can identify an existing file as a file and not a directory', function (): void {
    $filesystem = Container::singleton(\RWatch\Filesystem\Contracts\FilesystemInterface::class);
    $path = '/tmp/test.txt';
    $filesystem->setFileConfig(
        $path,
        [
            "isDirectory" => false,
            "isFile" => true,
            "exists" => true,
        ]
    );
    expect($filesystem->isFile($path))->toBeTrue()
        ->and($filesystem->isDirectory($path))->toBeFalse()
        ->and($filesystem->exists($path))->toBeTrue();
});

it('can identify a non-existent file as such', function (): void {
    $filesystem = Container::singleton(\RWatch\Filesystem\Contracts\FilesystemInterface::class);
    $path = '/does-not-exist';
    $filesystem->setFileConfig(
        $path,
        [
            "isDirectory" => false,
            "isFile" => false,
            "exists" => false,
        ]
    );
    expect($filesystem->isFile($path))->toBeFalse()
        ->and($filesystem->isDirectory($path))->toBeFalse()
        ->and($filesystem->exists($path))->toBeFalse();
});

it('can get the directory of a full path', function (): void {
    $filesystem = Container::singleton(\RWatch\Filesystem\Contracts\FilesystemInterface::class);
    $directory = '/tmp/sub-directory';
    $path = $directory . '/test.txt';
    $filesystem->setFileConfig(
        $path,
        [
            "isDirectory" => false,
            "isFile" => false,
            "exists" => false,
        ]
    );
    expect($filesystem->getDirectory($path))->toBe($directory);
});

it('can join paths', function (): void {
    $filesystem = Container::singleton(\RWatch\Filesystem\Contracts\FilesystemInterface::class);
    $directory = '/tmp/sub-directory';
    $file = 'test.txt';
    $path = "$directory/$file";
    expect($filesystem->join($directory, $file))->toBe($path);
});
