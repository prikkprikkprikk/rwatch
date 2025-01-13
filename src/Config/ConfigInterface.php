<?php

namespace Dwatch\Config;

interface ConfigInterface {
    public function getServer(): string;
    public function getUsername(): string;
    /** @return array<string> */
    public function getProjects(): array;
}
