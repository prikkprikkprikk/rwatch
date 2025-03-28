<?php

declare(strict_types=1);

beforeEach(function () {
   $this->command = new \RWatch\Command\CreateConfigFilePromptCommand();
});

it('exits the program if the answer is negative', function () {

    $io = new \RWatch\IO\TestIO([
        'Create config file? (y/n)' => false
    ]);

    expect($this->command->execute($io))->toBeNull();
});

it('returns the next command if the answer is positive', function () {

    $io = new \RWatch\IO\TestIO([
        'Create config file? (y/n)' => true
    ]);

    expect($this->command->execute($io))->toBeInstanceOf(\RWatch\Command\AskForServerNameCommand::class);
});