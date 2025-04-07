<?php

declare(strict_types=1);

use RWatch\Shell\ShellExecutor;

it('can successfully execute a shell command and return 0', function () {
    $executor = new ShellExecutor();
    $result = $executor->execute('true');
    expect($result)->toBe(0);
});

it('can successfully execute a shell command and return 1', function () {
    $executor = new ShellExecutor();
    $result = $executor->execute('false');
    expect($result)->toBe(1);
});

it('returns 0 when the command succeeds', function () {
    $executor = new ShellExecutor();
    $result = $executor->execute('ls');
    expect($result)->toBe(0);
});

it('returns 1 when the command fails', function () {
    $executor = new ShellExecutor();
    $result = $executor->execute('nonexistentcommand');
    expect($result)->not()->toBe(0);
});
