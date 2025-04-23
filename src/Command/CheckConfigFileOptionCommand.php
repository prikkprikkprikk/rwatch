<?php

declare(strict_types=1);

namespace RWatch\Command;

use RWatch\Command\Contracts\CommandInterface;

class CheckConfigFileOptionCommand implements CommandInterface {

    /**
     * As a first step in starting the app, check if there is any command line argument
     * for an alternative config file.
     *
     * @inheritDoc
     */
    public function execute(): ?CommandInterface {
        return null;
    }
}