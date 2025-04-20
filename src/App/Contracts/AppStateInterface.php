<?php

declare(strict_types=1);

namespace RWatch\App\Contracts;

use RWatch\Config\ConfigInterface;

interface AppStateInterface {

    public function loadConfig(ConfigInterface $config): void;

    public function setServer(string $server): void;

    public function getServer(): ?string;

    public function setUsername(string $username): void;

    public function getUsername(): ?string;

    public function setProject(string $project): void;

    public function getProject(): ?string;
}