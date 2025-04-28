<?php

declare(strict_types=1);

use RWatch\App\Contracts\AppStateInterface;
use RWatch\Config\ConfigInterface;
use RWatch\Container\Container;

it('can create an AppState object', function (): void {
    $appState = Container::singleton(AppStateInterface::class);
    expect($appState)->toBeInstanceOf(AppStateInterface::class)
        ->and($appState->getServer())->toBe('testServer')
        ->and($appState->getUsername())->toBe('testUsername')
        ->and($appState->getProject())->toBe('testProject');
});

it('can create an AppState object given a Config object', function (): void {
    $config = Container::singleton(ConfigInterface::class);
    $config->loadConfigFromArray([
        'server' => 'customTestServer',
        'username' => 'customTestUsername',
        'project' => 'customTestProject',
    ]);
    $appState = Container::singleton(AppStateInterface::class);
    $appState->loadConfig();
    expect($appState->getServer())->toBe('customTestServer')
        ->and($appState->getUsername())->toBe('customTestUsername')
        ->and($appState->getProject())->toBe('customTestProject');
});

it('can set the various properties to new values', function (): void {
    $appState = Container::singleton(AppStateInterface::class);
    $appState->setServer('anotherTestServer');
    $appState->setUsername('anotherTestUsername');
    $appState->setProject('anotherTestProject');
    expect($appState->getServer())->toBe('anotherTestServer')
        ->and($appState->getUsername())->toBe('anotherTestUsername')
        ->and($appState->getProject())->toBe('anotherTestProject');
});