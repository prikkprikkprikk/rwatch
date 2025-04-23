<?php

declare(strict_types=1);

namespace RWatch\Command;

use RWatch\Command\Contracts\CommandInterface;
use RWatch\Config\ConfigFile;
use RWatch\Config\ConfigFilePath;
use RWatch\Filesystem\Contracts\FilesystemInterface;
use function Symfony\Component\String\s;

class LoadConfigFileCommand implements CommandInterface {

    protected ?string $configFilePathString = null;
    protected ConfigFilePath|string|null $configFilePath;
    protected ConfigFile $configFile;
    private(set) FilesystemInterface $filesystem;

    /**
     * Creates a LoadConfigFileCommand either from a ConfigFilePath or a file path string.
     * If no path is given, try to load from default path.
     *
     * @param ConfigFilePath|string|null $configFilePath
     */
    public function __construct(ConfigFilePath|string|null $configFilePath = null) {
        $this->configFilePath = $configFilePath;
    }
    /**
     * @inheritDoc
     */
    public function execute(): CommandInterface {
        try {
            if ($this->configFilePath === null) {
                $this->configFilePath = ConfigFilePath::getDefaultConfigFilePath();
            } elseif (is_string($this->configFilePath)) {
                $this->configFilePathString = $this->configFilePath;
                $this->configFilePath = new ConfigFilePath(path: $this->configFilePathString);
            }
            $this->configFile = new ConfigFile(configFilePath: $this->configFilePath);
        } catch (\Exception $e) {
            return new PauseCommand("Failed to load config file: {$e->getMessage()}", null);
        }
        return new HydrateAppStateCommand($this->configFile);
    }
}