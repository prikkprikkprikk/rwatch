<?php
declare(strict_types=1);

namespace RWatch\Config;

use RWatch\CommandLineOptions\CommandLineOptions;

class Config implements ConfigInterface {

    private ConfigFilePath $configFilePath;

    /** @var array<string, string|null> */
    private array $config = [
        'server' => null,
        'username' => null,
        'project' => null,
    ];

    public function __construct(ConfigFilePath $configFilePath) {
        $this->configFilePath = $configFilePath;
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

    public function getServer(): string|null {
        return $this->config['server'];
    }

    public function getUsername(): string|null {
        return $this->config['username'];
    }

    public function getProject(): ?string {
        return $this->config['project'];
    }

    /**
     * Checks if all required configs are present, either as command line arguments or in the config file.
     */
    // private function checkForConfigs(): void {
    //     $commandLineOptions = CommandLineOptions::getInstance();
    //     foreach ($this->config as $configKey) {
    //         $this->config[$configKey] = $commandLineOptions->getOption($configKey);
    //     }
    //     // If all configs are not set, check for the config file
    //     // if (array_sum(array_map(callback: function ($value) {
    //     //     return $value !== null;
    //     // }, array: $this->config)) === 0) {
    //     //     $this->loadConfigFromFile();
    //     // }
    // }

    public function getProjects(): array {
        // TODO: Implement getProjects() method.
        return [];
    }
}