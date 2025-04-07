<?php

declare(strict_types=1);

use Mockery\MockInterface;
use RWatch\Command\FetchSymlinksFromServerCommand;
use RWatch\Command\StartNpmRunWatchCommand;
use RWatch\IO\TestIO;
use RWatch\Shell\ShellExecutorInterface;

it('runs `npm run watch`', function () {
    $config = getTestConfig();
    $executor = Mockery::mock(\RWatch\Shell\ShellExecutorInterface::class);
    /** @var ShellExecutorInterface|MockInterface $executor */
    $executor->shouldReceive('execute')
        ->with('ssh -t testUsername@testServer "cd -P ~/testProject && pwd && npm run watch"')
        ->andReturn(0);

    $command = new StartNpmRunWatchCommand(config: $config, shellExecutor: $executor);
    $nextCommand = $command->execute(new TestIO());

    expect($nextCommand)->toBeInstanceOf(FetchSymlinksFromServerCommand::class);
});

it('returns null when command fails', function () {
    $config = getTestConfig();
    $executor = Mockery::mock(\RWatch\Shell\ShellExecutorInterface::class);
    /** @var ShellExecutorInterface|MockInterface $executor */
    $executor->shouldReceive('execute')
        ->with('ssh -t testUsername@testServer "cd -P ~/testProject && pwd && npm run watch"')
        ->andReturn(1);

    $command = new StartNpmRunWatchCommand(config: $config, shellExecutor: $executor);
    $nextCommand = $command->execute(new TestIO());

    expect($nextCommand)->toBeNull();
});
