<?php

declare(strict_types=1);

use RWatch\App\AppState;
use RWatch\Config\Config;

it('can create an AppState object given a Config object', function () {
    $config = new Config([
        'server' => 'testServer',
        'username' => 'testUsername',
        'project' => 'testProject',
    ]);

    $appState = new AppState($config);
    expect($appState->getServer())->toBe('testServer')
        ->and($appState->getUsername())->toBe('testUsername')
        ->and($appState->getProject())->toBe('testProject');
});

it('can configure the AppState object by loading a Config object', function () {
    $config = new Config([
        'server' => 'testServer',
        'username' => 'testUsername',
        'project' => 'testProject',
    ]);
    $appState = new AppState();
    $appState->loadConfig($config);
    expect($appState->getServer())->toBe('testServer')
        ->and($appState->getUsername())->toBe('testUsername')
        ->and($appState->getProject())->toBe('testProject');
});

it('can create an AppAtate object with no given Config object', function () {
    $appState = new AppState();
    expect($appState->getServer())->toBeNull()
        ->and($appState->getUsername())->toBeNull()
        ->and($appState->getProject())->toBeNull();
});

it('can set and get the various properties', function () {
    $appState = new AppState();
    $appState->setServer('testServer');
    $appState->setUsername('testUsername');
    $appState->setProject('testProject');
    expect($appState->getServer())->toBe('testServer')
        ->and($appState->getUsername())->toBe('testUsername')
        ->and($appState->getProject())->toBe('testProject');
});