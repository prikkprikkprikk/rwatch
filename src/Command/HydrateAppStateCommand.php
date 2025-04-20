<?php

declare(strict_types=1);

namespace RWatch\Command;

use RWatch\App\AppState;
use RWatch\App\Contracts\AppStateInterface;
use RWatch\Command\Contracts\CommandInterface;
use RWatch\Config\Config;
use RWatch\IO\IOInterface;

class HydrateAppStateCommand implements CommandInterface {

    protected AppStateInterface $appState;

    public function __construct(protected Config $config) {
    }

    /**
     * @param IOInterface $io
     * @return CommandInterface|null
     */
    public function execute(IOInterface $io): ?CommandInterface {
        $this->appState = AppState::getInstance();
        $this->appState->loadConfig($this->config);

        return new StartNpmRunWatchCommand();
    }
}
