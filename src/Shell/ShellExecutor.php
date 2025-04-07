<?php

declare(strict_types=1);

namespace RWatch\Shell;

/**
 * Takes a shell command as an argument and executes it.
 *
 * @return integer The return value from the command, or 1 on failure.
 */
final class ShellExecutor implements ShellExecutorInterface {

    public function execute(string $command): int {
        $return_var = null;
        passthru($command, $return_var);
        return is_int($return_var) ? $return_var : 1;
    }
}