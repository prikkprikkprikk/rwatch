<?php

declare(strict_types=1);

namespace RWatch\App;

use RWatch\App\Contracts\AppStateInterface;
use RWatch\Config\Config;
use RWatch\Config\ConfigInterface;

class AppState implements AppStateInterface {

    protected static AppStateInterface|null $instance = null;
    protected ?string $server = null;
    protected ?string $username = null;
    protected ?string $project = null;

    protected function __construct(
        protected ?ConfigInterface $config = null
    ) {
        if ($config !== null) {
            $this->loadConfig($config);
        } else {
            $this->config = new Config();
        }
    }

    /**
     * Return singleton instance.
     *
     * @return AppStateInterface
     */
    public static function getInstance(): AppStateInterface {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function loadConfig(ConfigInterface $config): void {
        $this->config = $config;
        $this->setServer($config->getServer());
        $this->setUsername($config->getUsername());
        $this->setProject($config->getProject());
    }

    public function setServer(?string $server): void {
        $this->server = $server;
    }

    public function getServer(): ?string {
        return $this->server;
    }

    public function setUsername(?string $username): void {
        $this->username = $username;
    }

    public function getUsername(): ?string {
        return $this->username;
    }

    public function setProject(?string $project): void {
        $this->project = $project;
    }

    public function getProject(): ?string {
        return $this->project;
    }

    public static function destroy(): void {
        self::$instance = null;
    }
}