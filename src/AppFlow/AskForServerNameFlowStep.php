<?php

declare(strict_types=1);

namespace RWatch\AppFlow;

use RWatch\AppFlow\Contracts\FlowStepInterface;
use RWatch\Container\Container;
use RWatch\IO\IOInterface;

class AskForServerNameFlowStep implements FlowStepInterface {

    /**
     * Execute the flow step, and return the next flow step to be executed,
     * or null if the program should exit.
     *
     * @return FlowStepInterface|null
     */
    public function execute(): ?FlowStepInterface {
        $io = Container::singleton(IOInterface::class);

        $io->ask("Enter the server name:");

        return null;
    }
}
