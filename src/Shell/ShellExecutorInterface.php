<?php

declare(strict_types=1);

namespace RWatch\Shell;

use RWatch\Shell\Enum\ExitCodes;

interface ShellExecutorInterface {

    /**
     * Executes the given command.
     * Returns 1 if the return value from the command is non-integer.
     *
     * @param string $command The command to execute.
     * @return ExitCodes The return value from the command as an enum.
     */
    public function execute(string $command): ExitCodes;
}