<?php

declare(strict_types=1);

namespace RWatch\Command;

use RWatch\Config\ConfigInterface;
use RWatch\IO\IOInterface;
use function Laravel\Prompts\pause;

class AskForServerNameCommand extends ConfigAwareCommand {

    /**
     * Execute the command, and return the next command to be executed,
     * or null if the program should exit.
     *
     * @param IOInterface $io
     * @return CommandInterface|null
     */
    public function execute(IOInterface $io): ?CommandInterface {
        $io->ask("Enter the server name:");

        return null;
    }
}