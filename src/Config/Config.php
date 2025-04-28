<?php
declare(strict_types=1);

namespace RWatch\Config;

use RuntimeException;

class Config implements ConfigInterface {

    /** @var array<string, ?string> */
    private array $config = [
        'server' => null,
        'username' => null,
        'project' => null,
    ];
    private ConfigFilePath $configFilePath;
    private ConfigFile $configFile;

    /**
     * Create a Config object either from an array, a ConfigFile, a ConfigFilePath or a path as a string.
     * If no source is given, a default ConfigFile is created.
     *
     * @param array<string, ?string>|ConfigFile|ConfigFilePath|string|null $configSource
     */
    public function __construct(array|ConfigFile|ConfigFilePath|string|null $configSource = null) {
        if (is_array($configSource)) {
            $this->loadConfigFromArray($configSource);
            return;
        }

        if ($configSource === null) {
            $this->configFilePath = ConfigFilePath::getDefaultConfigFilePath();
            $this->configFile = new ConfigFile(configFilePath: $this->configFilePath);
        } elseif (is_string($configSource)) {
            $this->configFilePath = new ConfigFilePath(path: $configSource);
            $this->configFile = new ConfigFile(configFilePath: $this->configFilePath);
        } elseif (is_a($configSource, ConfigFilePath::class)) {
            $this->configFilePath = $configSource;
            $this->configFile = new ConfigFile(configFilePath: $this->configFilePath);
        } elseif ($configSource instanceof ConfigFile) {
            $this->configFile = $configSource;
        }

        $this->loadConfigFromFile();
    }

    /**
     *
     */
    public function loadConfigFromFile(): void {
        if (!isset($this->configFile)) {
            return;
        }
        $configArray = json_decode($this->configFile->getContents(), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException("Failed to parse config file: " . json_last_error_msg());
        }
        if (!is_array($configArray)) {
            throw new RuntimeException("Config file is not an array");
        }
        $this->loadConfigFromArray($configArray);
    }

    /**
     * @param array<mixed, mixed> $configArray
     */
    public function loadConfigFromArray(array $configArray): void {
        foreach (array_keys($this->config) as $setting) {
            if (!isset($configArray[$setting])) {
                continue;
            }
            if (is_string($configArray[$setting])) {
                $this->config[$setting] = $configArray[$setting];
            } elseif (is_int($configArray[$setting])) {
                $this->config[$setting] = (string)$configArray[$setting];
            }
        }
    }

    /**
     * @return array<string, ?string>
     */
    public function toArray(): array {
        return $this->config;
    }

    public function setServer(string $server): void {
        $this->config['server'] = $server;
    }

    public function setUsername(string $username): void {
        $this->config['username'] = $username;
    }

    public function getServer(): ?string {
        return $this->config['server'];
    }

    public function getUsername(): ?string {
        return $this->config['username'];
    }

    public function getProject(): ?string {
        return $this->config['project'];
    }

    public function setProject(string $project): void {
        $this->config['project'] = $project;
    }
}