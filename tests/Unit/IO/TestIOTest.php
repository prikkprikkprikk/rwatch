<?php

declare(strict_types=1);

namespace RWatch\Tests\Unit\IO;

use RWatch\IO\TestIO;

test('ask should answer a canned response', function (): void {

    $cannedResponses = [
        'Server name:' => 'd22',
        'Select project:' => '1',
    ];

    $io = new TestIO(cannedResponses: $cannedResponses);

    expect($io->ask('Server name:'))->toBe('d22');
});

test('ask should respond with an empty string if no canned response is found', function (): void {
    $io = new TestIO();
    expect($io->ask('Server name:'))->toBe('');
});

test('select should reply with a canned response', function (): void {
    $cannedResponses = [
        'Select project:' => '1',
    ];
    $io = new TestIO(cannedResponses: $cannedResponses);
    expect($io->select('Select project:', []))->toBe('1');
});

test('select should reply with an empty string if no canned response is found', function (): void {
    $io = new TestIO();
    expect($io->select('Select project:', []))->toBe('');
});

test('confirm should reply with a canned response', function (): void {
    $cannedResponses = [
        'Confirm action?' => true,
    ];

    $io = new TestIO(cannedResponses: $cannedResponses);

    expect($io->confirm('Confirm action?'))->toBe(true);
});

it('can display a pause message', function (): void {
    $io = new TestIO([]);

    expect($io->pause('Press enter to continue ...'))->toBeNull();
});

it('can echo a message', function (): void {
    $io = new TestIO([]);

    expect($io->echo('Test message'))->toBeNull();
});
