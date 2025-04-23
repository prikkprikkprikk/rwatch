<?php

declare(strict_types=1);

namespace RWatch\Command;

use RWatch\Command\Contracts\CommandInterface;

class LaunchAppCommand implements Contracts\CommandInterface {

    /**
     * @inheritDoc
     */
    public function execute(): ?CommandInterface {
        return new LoadConfigFileCommand();
    }
}