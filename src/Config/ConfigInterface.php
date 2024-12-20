<?php

namespace Dwatch\Config;

interface ConfigInterface {
    public function getServer(): string;
    public function getUsername(): string;
    public function getProjects(): array;
}
