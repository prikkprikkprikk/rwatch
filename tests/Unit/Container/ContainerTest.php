<?php

declare(strict_types=1);

use RWatch\Container\Container;
use RWatch\Filesystem\Contracts\FilesystemInterface;
use RWatch\Filesystem\Filesystem;
use RWatch\Filesystem\TestFilesystem;


beforeEach(function () {
    Container::reset();
});

it('can instantiate and return a Filesystem singleton', function () {

    Container::bind(
        FilesystemInterface::class,
        Filesystem::class,
    );

    $filesystem = Container::singleton(FilesystemInterface::class);

    expect($filesystem)->toBeInstanceOf(Filesystem::class);
});

it('can instantiate and return a TestFilesystem singleton', function () {

    Container::bind(
        FilesystemInterface::class,
        TestFilesystem::class,
    );

    $filesystem = Container::singleton(FilesystemInterface::class);

    expect($filesystem)->toBeInstanceOf(TestFilesystem::class);
});
