<?php

declare(strict_types=1);

use RWatch\Command\ExitProgramCommand;
use RWatch\IO\TestIO;

it('returns null to signify that the program should exit', function () {

    $command = new ExitProgramCommand();

    $io = new TestIO([
        'Exiting program.' => true,
    ]);

    expect($command->execute($io))->toBeNull();
});
