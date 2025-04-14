<?php

declare(strict_types=1);

use Mockery\MockInterface;
use RWatch\Command\FetchSymlinksFromServerCommand;
use RWatch\Command\PauseCommand;
use RWatch\Command\StartNpmRunWatchCommand;
use RWatch\IO\TestIO;
use RWatch\Shell\Enum\ExitCodes;
use RWatch\Shell\ShellExecutorInterface;

beforeEach(function () {
    ob_start();
});

afterEach(function () {
    ob_end_clean();
});

it('runs `npm run watch`', function () {
    $appState = getTestState();
    $executor = Mockery::mock(\RWatch\Shell\ShellExecutorInterface::class);
    /** @var ShellExecutorInterface|MockInterface $executor */
    $executor->shouldReceive('execute')
        ->with('ssh -t testUsername@testServer "cd -P ~/testProject && pwd && npm run watch"')
        ->andReturn(ExitCodes::SSH_CONNECTION_CLOSED);

    $command = new StartNpmRunWatchCommand(appState: $appState, shellExecutor: $executor);
    $nextCommand = $command->execute(new TestIO());

    expect($nextCommand)->toBeInstanceOf(FetchSymlinksFromServerCommand::class);
});

it('pauses with a message when command fails', function () {
    $appState = getTestState();
    $executor = Mockery::mock(\RWatch\Shell\ShellExecutorInterface::class);
    /** @var ShellExecutorInterface|MockInterface $executor */
    $executor->shouldReceive('execute')
        ->with('ssh -t testUsername@testServer "cd -P ~/testProject && pwd && npm run watch"')
        ->andReturn(ExitCodes::GENERIC_ERROR);

    $command = new StartNpmRunWatchCommand(appState: $appState, shellExecutor: $executor);
    $nextCommand = $command->execute(new TestIO());

    expect($nextCommand)->toBeInstanceOf(PauseCommand::class);
});
