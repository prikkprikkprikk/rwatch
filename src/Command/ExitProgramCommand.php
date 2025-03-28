<?php

declare(strict_types=1);

namespace RWatch\Command;

use RWatch\Command\CommandInterface;
use RWatch\IO\IOInterface;

class ExitProgramCommand implements CommandInterface {

    /**
     * @inheritDoc
     */
    public function execute(IOInterface $io): ?CommandInterface {
        return null;
    }
}