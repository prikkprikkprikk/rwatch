<?php

declare(strict_types=1);

namespace RWatch\Command;

use RWatch\Command\Contracts\CommandInterface;

class CheckCommandLineArgumentsCommand implements CommandInterface {

    public function execute(): ?CommandInterface {
        return null;
    }
}
