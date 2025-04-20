<?php

declare(strict_types=1);

namespace RWatch\Command;

use RWatch\Command\Contracts\CommandInterface;
use RWatch\Config\Config;
use RWatch\Config\ConfigFilePath;
use RWatch\IO\IOInterface;

class LoadConfigFileCommand implements CommandInterface {

    protected ?string $configFilePathString = null;
    protected ?ConfigFilePath $configFilePath = null;
    protected Config $config;

    /**
     * Creates a LoadConfigFileCommand either from a ConfigFilePath or a file path string.
     *
     * @param ConfigFilePath|string $configFilePath
     */
    public function __construct(ConfigFilePath|string $configFilePath) {
        if (is_string($configFilePath)) {
            $this->configFilePathString = $configFilePath;
        } else {
            $this->configFilePath = $configFilePath;
        }
    }
    /**
     * @inheritDoc
     */
    public function execute(IOInterface $io): CommandInterface {
        try {
            if (is_string($this->configFilePathString)) {
                $this->configFilePath = new ConfigFilePath($this->configFilePathString);
            }
            $this->config = new Config($this->configFilePath);
        } catch (\Exception $e) {
            return new PauseCommand("Failed to load config file: {$e->getMessage()}", null);
        }
        return new HydrateAppStateCommand($this->config);
    }
}