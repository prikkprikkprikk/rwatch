<?php
declare(strict_types=1);

namespace RWatch\Config;

class Config implements ConfigInterface {

    /** @var array<string, ?string> */
    private array $config = [
        'server' => null,
        'username' => null,
        'project' => null,
    ];

    /**
     * @param array<string, ?string>|null $configArray
     */
    public function __construct(?array $configArray = null) {
        if (is_array($configArray)) {
            $this->fromArray($configArray);
        }
    }

    /**
     * @param array<string, ?string> $configArray
     * @return void
     */
    public function fromArray(array $configArray): void {
        foreach (array_keys($this->config) as $setting) {
            if (isset($configArray[$setting])) {
                if (is_string($configArray[$setting]) || is_numeric($configArray[$setting])) {
                    $this->config[$setting] = (string)$configArray[$setting];
                }
                else {
                    $this->config[$setting] = null;
                }
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