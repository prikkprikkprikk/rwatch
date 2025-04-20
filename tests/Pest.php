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
use Symfony\Component\Filesystem\Filesystem;

/**
 * Creates an AppState object with test data.
 *
 * @return AppStateInterface
 */
function getTestState(): AppStateInterface {
    $appState = AppState::getInstance();
    $appState->setProject('testProject');
    $appState->setServer('testServer');
    $appState->setUsername('testUsername');
    return $appState;
}

/**
 * Creates a temporary configuration file in JSON format with an empty array.
 *
 * @return string The full path to the created configuration file.
 */
function createEmptyTestConfigFile(): string {
    return createTestConfigFile([]);
}

/**
 * Creates a temporary configuration file with the supplied array as its contents.
 *
 * @param array<string, string|int> $data Data to be saved to the file.
 * @return string The full path to the created configuration file.
 */
function createTestConfigFile(array $data = []): string {
    $filesystem = new Filesystem();
    $tempDir = createTempDir();
    $tempFilename = $tempDir . '/config.json';
    if (file_exists($tempFilename)) {
        unlink($tempFilename);
    }
    $filesystem->touch($tempFilename);
    $dataAsJson = json_encode($data);
    if ($dataAsJson === false) {
        throw new \RuntimeException('Could not encode data to JSON');
    }
    $filesystem->appendToFile($tempFilename, $dataAsJson);
    return $tempFilename;
};

function createTempDir(): string {
    $tempDir = sys_get_temp_dir() . '/rwatch_configtest';
    if (!file_exists($tempDir)) {
        mkdir($tempDir, 0755);
    }
    return realpath($tempDir);
}

function deleteTestConfigFile(string $filename): void {
    $filesystem = new Filesystem();
    $filesystem->remove($filename);
}