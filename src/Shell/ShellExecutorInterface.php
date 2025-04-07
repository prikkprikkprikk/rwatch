<?php

declare(strict_types=1);

namespace RWatch\Shell;

interface ShellExecutorInterface {

    /**
     * Executes the given command.
     * Returns 1 if the return value from the command is non-integer.
     *
     * @param string $command The command to execute.
     * @return integer The return value from the command.
     */
    public function execute(string $command): int;
}