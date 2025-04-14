<?php

namespace RWatch\Config;

interface ConfigInterface {

    /**
     * @param array<string, string|null> $configArray
     * @return void
     */
    public function fromArray(array $configArray): void;

    /**
     * @return array<string, ?string>
     */
    public function toArray(): array;

    public function setServer(string $server): void;

    public function getServer(): string|null;

    public function setUsername(string $username): void;

    public function getUsername(): string|null;

    public function setProject(string $project): void;

    public function getProject(): string|null;
}
