<?php

declare(strict_types=1);

use RWatch\Shell\Enum\ExitCodes;

it('creates the correct enum from supported values', function (int $value, ExitCodes $enum): void {
    expect(ExitCodes::tryFrom($value))->toBe($enum)
        ->and($enum->value)->toBe($value);
})->with([
    [
        'value' => 0,
        'enum' => ExitCodes::SUCCESS
    ],
    [
        'value' => 1,
        'enum' => ExitCodes::GENERIC_ERROR
    ],
    [
        'value' => 255,
        'enum' => ExitCodes::SSH_CONNECTION_CLOSED
    ],
]);

it('returns null for unsupported values', function (int $value): void {
    expect(ExitCodes::tryFrom($value))->toBeNull();
})->with([
    [ 'value' => 42 ]
]);