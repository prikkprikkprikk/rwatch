<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

// pest()->extend(Tests\TestCase::class)
//     ->in('Unit', 'Feature');

pest()->beforeEach(function (): void {
    bootstrapTestEnvironment();
})->in('Unit', 'Feature');


/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

// expect()->extend('toBeOne', function () {
//     return $this->toBe(1);
// });

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

use RWatch\App\AppState;
use RWatch\App\Contracts\AppStateInterface;
use RWatch\CommandLineOptions\CommandLineOptions;
use RWatch\CommandLineOptions\CommandLineOptionsInterface;
use RWatch\Config\Config;
use RWatch\Config\ConfigInterface;
use RWatch\Container\Container;
use RWatch\Filesystem\Contracts\FilesystemInterface;
use RWatch\Filesystem\TestFilesystem;
use RWatch\IO\IOInterface;
use RWatch\IO\TestIO;
use RWatch\Shell\ShellExecutor;
use RWatch\Shell\ShellExecutorInterface;

function bootstrapTestEnvironment(): void {
    Container::reset();

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
        AppStateInterface::class,
        AppState::class,
    );
    Container::bind(
        ShellExecutorInterface::class,
        ShellExecutor::class,
    );
    Container::bind(
        FilesystemInterface::class,
        TestFilesystem::class)
    ;
    Container::bind(
        IOInterface::class,
        TestIO::class
    );

    $filesystem = Container::singleton(FilesystemInterface::class);

    // Set up default config file response
    $filesystem->setFileConfig(
        getDefaultConfigFilePath(),
        [
            'isDirectory' => false,
            'isFile' => true,
            'exists' => true,
            'isReadable' => true,
            'contents' => '{
                "server": "testServer",
                "username": "testUsername",
                "project": "testProject"
            }'
        ]
    );

    $appState = Container::singleton(AppStateInterface::class);
    $appState->loadConfig();
}

/**
 * Get the default config file path.
 *
 * @return string
 */
function getDefaultConfigFilePath(): string {
    return getDefaultConfigDirectory() . '/config.json';
}

/**
 * Get the default config directory.
 *
 * @return string
 */
function getDefaultConfigDirectory(): string {
    return getenv('HOME') . '/.config/rwatch';
}
