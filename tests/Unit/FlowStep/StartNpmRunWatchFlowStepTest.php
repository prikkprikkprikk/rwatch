<?php

declare(strict_types=1);

use Mockery\MockInterface;
use RWatch\AppFlow\FetchSymlinksFromServerFlowStep;
use RWatch\AppFlow\PauseFlowStep;
use RWatch\AppFlow\StartNpmRunWatchFlowStep;
use RWatch\Shell\Enum\ExitCodes;
use RWatch\Shell\ShellExecutorInterface;

beforeEach(function (): void {
    ob_start();
});

afterEach(function (): void {
    ob_end_clean();
});

it('runs `npm run watch`', function (): void {
    $executor = Mockery::mock(\RWatch\Shell\ShellExecutorInterface::class);
    /** @var ShellExecutorInterface|MockInterface $executor */
    $executor->shouldReceive('execute')
        ->with('ssh -t testUsername@testServer "cd -P ~/testProject && pwd && npm run watch"')
        ->andReturn(ExitCodes::SSH_CONNECTION_CLOSED);

    $flowStep = new StartNpmRunWatchFlowStep();
    $nextFlowStep = $flowStep->execute();

    expect($nextFlowStep)->toBeInstanceOf(FetchSymlinksFromServerFlowStep::class);
});

it('pauses with a message when command fails', function (): void {
    $executor = Mockery::mock(\RWatch\Shell\ShellExecutorInterface::class);
    \RWatch\Container\Container::bind(
        ShellExecutorInterface::class,
        $executor,
    );
    $executor->shouldReceive('execute')
        ->with('ssh -t testUsername@testServer "cd -P ~/testProject && pwd && npm run watch"')
        ->andReturn(ExitCodes::GENERIC_ERROR);

    $flowStep = new StartNpmRunWatchFlowStep();
    $nextFlowStep = $flowStep->execute();

    expect($nextFlowStep)->toBeInstanceOf(PauseFlowStep::class);
});
