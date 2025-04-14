<?php

declare(strict_types=1);

namespace RWatch\Shell;

use RWatch\Shell\Enum\ExitCodes;

/**
 * Takes a shell command as an argument and executes it.
 *
 * @return integer The return value from the command, or 1 on failure.
 */
final class ShellExecutor implements ShellExecutorInterface {

    /**
     * Execute the given string with passthru() and return an exit code enum.
     *
     * @param string $command
     * @return ExitCodes
     */
    public function execute(string $command): ExitCodes {
        $return_var = null;
        passthru($command, $return_var);
        echo(var_export($return_var, true));
        return ExitCodes::tryFrom((int)$return_var) ?? ExitCodes::GENERIC_ERROR;
    }
}