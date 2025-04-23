<?php

declare(strict_types=1);

namespace RWatch\Command;

use RWatch\App\Contracts\AppStateInterface;
use RWatch\Command\Contracts\CommandInterface;
use RWatch\Config\Config;
use RWatch\Config\ConfigFile;
use RWatch\Container\Container;

class HydrateAppStateCommand implements CommandInterface {

    /**
     * @return CommandInterface|null
     */
    public function execute(): ?CommandInterface {
        return new FetchSymlinksFromServerCommand();
    }
}
