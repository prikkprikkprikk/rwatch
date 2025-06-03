<?php

declare(strict_types=1);

use RWatch\AppFlow\Contracts\FlowStepInterface;
use RWatch\AppFlow\PauseFlowStep;

it('displays a message and returns the next flow step', function (): void {

    $flowStep = new PauseFlowStep(
        "Test message",
        Mockery::mock(FlowStepInterface::class)
    );

    expect($flowStep->execute())
        ->toBeInstanceOf(FlowStepInterface::class);
});

it('can return null to exit the program', function (): void {

    $flowStep = new PauseFlowStep(
        "Test message",
        null
    );

    expect($flowStep->execute())
        ->toBeNull();
});
