<?php
declare(strict_types=1);

use RWatch\Container\Container;
use RWatch\Filesystem\Contracts\FilesystemInterface;
use RWatch\Filesystem\Filesystem;

// Bind interfaces to implementations
Container::bind(FilesystemInterface::class, Filesystem::class);
