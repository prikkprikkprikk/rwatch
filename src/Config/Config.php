<?php
declare(strict_types=1);

namespace Dwatch\Config;

use Dwatch\CommandLineOptions\CommandLineOptions;

class Config implements ConfigInterface {

    private ConfigFilePath $configFilePath;

    private array $config = [
        'server' => null,
        'username' => null,
        'project' => null,
    ];

    public function __construct(ConfigFilePath $configFilePath) {
        $this->configFilePath = $configFilePath ?? ConfigFilePath::getDefaultConfigFilePath();
    }

    protected function ensureConfigDirAndFileExist(): void
    {
        if (!file_exists($this->configFilePath->directory())) {
            mkdir($this->configFilePath->directory(), 0755, true);
        }
            if (!file_exists($this->configFilePath->fullPath())) {
            // Save empty config values to new file
            $this->saveConfig();
        }
    }

    public function shouldPromptUser(): bool
    {
        return $this->config['server'] === '' || $this->config['username'] === '';
    }

    public function saveConfig(): void {
        file_put_contents(
            $this->configFilePath->fullPath(),
            json_encode($this->config, JSON_PRETTY_PRINT)
        );
    }

    public function setServer(string $server): void {
        $this->config['server'] = $server;
    }

    public function setUsername(string $username): void {
        $this->config['username'] = $username;
    }

    public function getServer(): string {
        return $this->config['server'];
    }

    public function getUsername(): string {
        return $this->config['username'];
    }

    public function getProject() {
        return $this->config['project'];
    }

    /**
     * Checks if all required configs are present, either as command line arguments or in the config file.
     */
    private function checkForConfigs(): void {
        $commandLineOptions = CommandLineOptions::getInstance();
        foreach ($this->config as $configKey) {
            $this->config[$configKey] = $commandLineOptions->getOption($configKey);
        }
        // If all configs are not set, check for the config file
        // if (array_sum(array_map(callback: function ($value) {
        //     return $value !== null;
        // }, array: $this->config)) === 0) {
        //     $this->loadConfigFromFile();
        // }
    }

    public function getProjects(): array {
        // TODO: Implement getProjects() method.
        return [];
    }
}