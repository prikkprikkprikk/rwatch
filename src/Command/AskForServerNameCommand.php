<?php

declare(strict_types=1);

namespace RWatch\Command;

use RWatch\Command\Contracts\CommandInterface;
use RWatch\Container\Container;
use RWatch\IO\IOInterface;

class AskForServerNameCommand extends ConfigAwareCommand {

    /**
     * Execute the command, and return the next command to be executed,
     * or null if the program should exit.
     *
     * @return CommandInterface|null
     */
    public function execute(): ?CommandInterface {
        $io = Container::singleton(IOInterface::class);

        $io->ask("Enter the server name:");

        return null;
    }
}