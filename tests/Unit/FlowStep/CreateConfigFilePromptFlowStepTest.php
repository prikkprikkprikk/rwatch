<?php

declare(strict_types=1);

use RWatch\IO\TestIO;

beforeEach(function (): void {
    // $this->app = new \RWatch\App\App();
    // $this->flowStep = new \RWatch\AppFlow\CreateConfigFilePromptFlowStep();
});

it('exits the program if the answer is negative', function (): void {

    $io = new TestIO([
        'Create config file? (y/n)' => false
    ]);

    expect($this->flowStep->execute($io))->toBeNull();
})->skip(message: 'Not implemented yet');

it('returns the next flow step if the answer is positive', function (): void {

    $io = new TestIO([
        'Create config file? (y/n)' => true
    ]);

    expect($this->flowStep->execute($io))->toBeInstanceOf(\RWatch\AppFlow\AskForServerNameFlowStep::class);
})->skip(message: 'Not implemented yet');
