<?php

declare(strict_types=1);

namespace RWatch\App;

use RWatch\App\Contracts\AppStateInterface;
use RWatch\Config\ConfigInterface;
use RWatch\Container\Container;

class AppState implements AppStateInterface {

    protected static AppStateInterface|null $instance = null;
    protected ?string $server = null;
    protected ?string $username = null;
    protected ?string $project = null;

    public function __construct() {
        $this->loadConfig();
    }

    public function loadConfig(): void {
        $config = Container::singleton(ConfigInterface::class);
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