<?php

declare(strict_types=1);

namespace RWatch\Command;

use RWatch\Command\Contracts\CommandInterface;
use RWatch\Config\ConfigFile;
use RWatch\Config\ConfigFilePath;
use RWatch\Filesystem\Contracts\FilesystemInterface;
use RWatch\Filesystem\Filesystem;
use RWatch\IO\IOInterface;
use function PHPUnit\Framework\assertInstanceOf;

class LoadConfigFileCommand implements CommandInterface {

    protected ?string $configFilePathString = null;
    protected ConfigFilePath|string|null $configFilePath = null;
    protected ConfigFile $configFile;
    protected FilesystemInterface $filesystem;

    /**
     * Creates a LoadConfigFileCommand either from a ConfigFilePath or a file path string.
     * If no path is given, try to load from default path.
     *
     * @param ConfigFilePath|string|null $configFilePath
     * @param FilesystemInterface|null $filesystem
     */
    public function __construct(ConfigFilePath|string|null $configFilePath = null, ?FilesystemInterface $filesystem = null) {
        $this->filesystem = $filesystem ?? new Filesystem();
        $this->configFilePath = $configFilePath;
    }
    /**
     * @inheritDoc
     */
    public function execute(IOInterface $io): CommandInterface {
        try {
            if ($this->configFilePath === null) {
                $this->configFilePath = ConfigFilePath::getDefaultConfigFilePath($this->filesystem);
            } elseif (is_string($this->configFilePath)) {
                $this->configFilePathString = $this->configFilePath;
                $this->configFilePath = new ConfigFilePath(path: $this->configFilePathString, filesystem: $this->filesystem);
            }
            $this->configFile = new ConfigFile(configFilePath: $this->configFilePath, filesystem: $this->filesystem);
        } catch (\Exception $e) {
            return new PauseCommand("Failed to load config file: {$e->getMessage()}", null);
        }
        return new HydrateAppStateCommand($this->configFile);
    }
}