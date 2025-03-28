<?php

declare(strict_types=1);

namespace RWatch\Command;

use RWatch\Config\Config;
use RWatch\IO\IOInterface;

interface CommandInterface {
    /**
     * Execute the command, and return the next command to be executed,
     * or null if the program should exit.
     *
     * @return CommandInterface|null
     */
    public function execute(IOInterface $io): ?CommandInterface;
}