<?php

declare(strict_types=1);

namespace RWatch\AppFlow;

use RWatch\AppFlow\Contracts\FlowStepInterface;
use RWatch\Config\ConfigFile;
use RWatch\Config\ConfigFilePath;
use RWatch\Filesystem\Contracts\FilesystemInterface;

class LoadConfigFileFlowStep implements FlowStepInterface {

    protected ?string $configFilePathString = null;
    protected ConfigFile $configFile;
    private(set) FilesystemInterface $filesystem;

    /**
     * Creates a LoadConfigFileFlowStep either from a ConfigFilePath or a file path string.
     * If no path is given, try to load from default path.
     *
     * @param ConfigFilePath|string|null $configFilePath
     */
    public function __construct(protected ConfigFilePath|string|null $configFilePath = null)
    {
    }
    /**
     * @inheritDoc
     */
    public function execute(): FlowStepInterface {
        try {
            if ($this->configFilePath === null) {
                $this->configFilePath = ConfigFilePath::getDefaultConfigFilePath();
            } elseif (is_string($this->configFilePath)) {
                $this->configFilePathString = $this->configFilePath;
                $this->configFilePath = new ConfigFilePath(path: $this->configFilePathString);
            }
            $this->configFile = new ConfigFile(configFilePath: $this->configFilePath);
        } catch (\Exception $e) {
            return new PauseFlowStep("Failed to load config file: {$e->getMessage()}", null);
        }
        return new HydrateAppStateFlowStep();
    }
}
