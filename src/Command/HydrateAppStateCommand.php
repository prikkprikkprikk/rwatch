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
     * @param IOInterface $io
     * @return CommandInterface|null
     */
    public function execute(IOInterface $io): ?CommandInterface {
        $this->config = new Config($this->configFile);
        $this->appState = AppState::getInstance();
        $this->appState->loadConfig($this->config);

        return new StartNpmRunWatchCommand();
    }
}
