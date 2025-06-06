<?php

declare(strict_types=1);

use RWatch\Shell\Enum\ExitCodes;
use RWatch\Shell\ShellExecutor;

beforeEach(function (): void {
    ob_start();
});

afterEach(function (): void {
    ob_end_clean();
});

it('can successfully execute a shell command and return 0', function (): void {
    $executor = new ShellExecutor();
    $result = $executor->execute('true');
    expect($result)->toBe(ExitCodes::SUCCESS);
});

it('can successfully execute a failing shell command and return GENERIC_ERROR', function (): void {
    $executor = new ShellExecutor();
    $result = $executor->execute('false');
    expect($result)->toBe(ExitCodes::GENERIC_ERROR);
    expect($result->isFailure())->toBe(true);
});

it('returns SUCCESS when the command succeeds', function (): void {
    $executor = new ShellExecutor();
    $result = $executor->execute('ls');
    expect($result)->toBe(ExitCodes::SUCCESS);
});

it('returns GENERIC_ERROR when trying to execute a non-existent command', function (): void {
    $executor = new ShellExecutor();
    $result = $executor->execute('non-existent-command 2>/dev/null');
    expect($result)->toBe(ExitCodes::GENERIC_ERROR);
});
