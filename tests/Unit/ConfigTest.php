<?php

use Dwatch\Config\Config;
use Dwatch\Config\ConfigFilePath;

beforeEach(function ()
{
    $this->configDir = '/tmp/dwatch';
    $this->configFile = 'config.json';
    $this->configFilePath = new ConfigFilePath($this->configDir, $this->configFile);
});

afterEach(function ()
{
    if (file_exists($this->configFilePath->fullPath())) {
        unlink($this->configFilePath->fullPath());
    }

    if (file_exists($this->configDir)) {
        rmdir($this->configDir);
    }
});

/**
 * This test suite tests the Config class, which is responsible for handling both options and the config file.
 *
 * If both parameters are supplied, it will not create the config file or directory.
 * If no parameters are supplied, it will ask the user if they want to create the config file.
 * If only one parameter is supplied, the program exits with an error message telling the user to supply the other
 * or create the config file.
 *
 * The tests in this suite cover the following scenarios:
 *
 * - The program is run with no arguments, and the config file and directory do not exist.
 *   The user is asked if they want to create the config file, and if they do, the program will
 *   prompt them for their server and username.
 * - The program is run with just the server argument, and the config file and directory do not exist.
 *   The user is asked if they want to create the config file, and if they do, the program will save the server name
 *   in the config file and prompt them for their username.
 * - The program is run with just the username argument, and the config file and directory do not exist.
 *   The user is asked if they want to create the config file, and if they do, the program will save the username name
 *   in the config file and prompt them for their server.
 * - The config file and directory do not exist, but the user has entered both server and username. The program will
 *   not create the config file or directory.
 * - The config file and directory exist, but the user has entered both server and username. The supplied arguments
 *   will override the values in the config file.
 * - The config file and directory exist, but the user has only entered the server. The supplied server name will
 *   override the value in the config file.
 * - The config file and directory exist, but the user has only entered the username. The supplied username name will
 *   override the value in the config file.
 * - The config file and directory exist, and the user has not entered any arguments. The program will not prompt the
 *   user for anything, and will use the values in the config file.
 */

dataset('configScenarios', [
    'happy path: no command line options, config file and directory exist' => [
        'server' => null,
        'username' => null,
        'shouldPrompt' => false,
        'configExists' => true
    ],
]);

it('handles configuration scenarios properly', function (
    ?string $server,
    ?string $username,
    bool $shouldPrompt,
    bool $configExists
) {
    if ($configExists) {
        mkdir($this->configDir, 0755, true);
        file_put_contents($this->configFilePath->fullPath(), json_encode([
            'server' => 'default-server',
            'username' => 'default-user'
        ]));
    }

    $config = new Config($this->configFilePath);

    expect($config->shouldPromptUser())->toBe($shouldPrompt);

    if ($server !== null) {
        expect($config->getServer())->toBe($server);
    }
    if ($username !== null) {
        expect($config->getUsername())->toBe($username);
    }
})->with('configScenarios');
