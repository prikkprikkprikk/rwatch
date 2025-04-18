<?php

declare(strict_types=1);

namespace RWatch\Config;

use RuntimeException;

class ConfigFile {

    protected string $fileContents = '';

    /**
     * @throws RuntimeException
     */
    public function __construct(private ConfigFilePath $configFilePath) {
        if (!$configFilePath->fileIsReadable()) {
            throw new RuntimeException("Config file is not readable");
        }

        $this->readFileContents();
    }

    public function getContents(): string {
        return $this->fileContents;
    }

    protected function readFileContents(): void {
        $this->fileContents = file_get_contents($this->configFilePath->fullPath());
    }
}