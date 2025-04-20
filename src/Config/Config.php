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
    private ConfigFile $configFile;

    /**
     * Create a Config object either from a given array or from a given file path,
     * which can be supplied either as a ConfigFilePath or as a string.
     *
     * @param array<string, ?string>|ConfigFilePath|string|null $configSource
     */
    public function __construct(array|ConfigFilePath|string|null $configSource = null) {
        if ($configSource === null) {
            return;
        }
        if (is_array($configSource)) {
            $this->loadConfigFromArray($configSource);
        } elseif (is_string($configSource) || (get_class($configSource) === ConfigFilePath::class)) {
            $this->loadConfigFromFile($configSource);
        }
    }

    /**
     *
     */
    public function loadConfigFromFile(ConfigFilePath|string $filePath): void {
        if (is_string($filePath)) {
            $filePath = new ConfigFilePath($filePath);
        }
        $this->configFile = new ConfigFile($filePath);
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
            } elseif (is_integer($configArray[$setting])) {
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