<?php

declare(strict_types=1);

namespace RWatch\Command;

use RWatch\App\AppState;
use RWatch\App\Contracts\AppStateInterface;
use RWatch\Command\Contracts\CommandInterface;
use RWatch\Config\Config;
use RWatch\Config\ConfigFile;
use RWatch\IO\IOInterface;

class HydrateAppStateCommand implements CommandInterface {

    protected AppStateInterface $appState;
    protected Config $config;

    public function __construct(protected ConfigFile $configFile) {
    }

    /**
     * @return CommandInterface|null
     */
    public function execute(): ?CommandInterface {
        return new StartNpmRunWatchCommand();
    }
}
