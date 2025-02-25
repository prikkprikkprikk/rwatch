<?php

namespace RWatch\Config;

interface ConfigInterface {
    public function getServer(): string|null;
    public function getUsername(): string|null;
    /** @return array<string> */
    public function getProjects(): array;
}
