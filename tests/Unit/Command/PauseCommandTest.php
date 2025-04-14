<?php

declare(strict_types=1);

use RWatch\Command\Contracts\CommandInterface;
use RWatch\Command\PauseCommand;
use RWatch\IO\TestIO;

it('displays a message and returns the next command', function () {

    $io = new TestIO([]);
    $command = new PauseCommand(
        "Test message",
        Mockery::mock(CommandInterface::class)
    );

    expect($command->execute($io))
        ->toBeInstanceOf(CommandInterface::class);
});

it('can return null to exit the program', function () {

    $io = new TestIO([]);
    $command = new PauseCommand(
        "Test message",
        null
    );

    expect($command->execute($io))
        ->toBeNull();
});