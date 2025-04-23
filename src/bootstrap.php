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
use RWatch\IO\ConsoleIO;
use RWatch\IO\IOInterface;
use RWatch\Shell\ShellExecutor;
use RWatch\Shell\ShellExecutorInterface;

// Bind interfaces to implementations
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
    Filesystem::class,
);
Container::bind(
    IOInterface::class,
    ConsoleIO::class,
);
Container::bind(
    AppStateInterface::class,
    AppState::class,
);
Container::bind(
    ShellExecutorInterface::class,
    ShellExecutor::class,
);
