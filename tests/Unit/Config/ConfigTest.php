<?php

use RWatch\Config\Config;
use RWatch\Config\Exception\InvalidOptionException;

/**
 * This test suite tests the Config class, which is responsible for holding the app's configuration values.
 *
 * If any values are missing, they should return null.
 *
 * If any invalid values are submitted, they should be silently ignored.
 */

dataset('configScenarios', [
    'Happy path: All values are supplied' => [
        'config' => [
            'server' => 'testServer',
            'username' => 'testUsername',
            'project' => 'testProject',
        ],
        'expected' => [
            'server' => 'testServer',
            'username' => 'testUsername',
            'project' => 'testProject',
        ]
    ],
    'Empty config supplied' => [
        'config' => [],
        'expected' => [
            'server' => null,
            'username' => null,
            'project' => null,
        ]
    ],
    'Missing server' => [
        'config' => [
            'username' => 'testUsername',
            'project' => 'testProject',
        ],
        'expected' => [
            'server' => null,
            'username' => 'testUsername',
            'project' => 'testProject',
        ]
    ],
    'Missing username' => [
        'config' => [
            'server' => 'testServer',
            'project' => 'testProject',
        ],
        'expected' => [
            'server' => 'testServer',
            'username' => null,
            'project' => 'testProject',
        ]
    ],
    'Missing project' => [
        'config' => [
            'server' => 'testServer',
            'username' => 'testUsername',
        ],
        'expected' => [
            'server' => 'testServer',
            'username' => 'testUsername',
            'project' => null,
        ]
    ],
    'Invalid config options' => [
        'config' => [
            'server' => 'testServer',
            'username' => 'testUsername',
            'project' => 'testProject',
            'invalidOption' => 'testInvalidOption',
            'anotherInvalidOption' => 'testAnotherInvalidOption',
        ],
        'expected' => [
            'server' => 'testServer',
            'username' => 'testUsername',
            'project' => 'testProject',
        ]
    ],
    'Wrong config value type' => [
        'config' => [
            'server' => 123,
            'username' => ['testUsername'],
            'project' => [456],
        ],
        'expected' => [
            'server' => '123',
            'username' => 'Array',
            'project' => 'Array',
        ]
    ]
]);

it('handles configuration scenarios properly', function (
    array $config,
    array $expected,
) {
    $config = new Config($config);

    expect($config->getServer())->toBe($expected['server'])
        ->and($config->getUsername())->toBe($expected['username'])
        ->and($config->getProject())->toBe($expected['project']);

})->with('configScenarios');
