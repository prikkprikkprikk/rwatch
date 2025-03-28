<?php

declare(strict_types=1);

namespace RWatch\Command;

use RWatch\IO\IOInterface;

class CheckCommandLineArgumentsCommand implements CommandInterface {

    public function execute(IOInterface $io): ?CommandInterface {
        return null;
    }
}
