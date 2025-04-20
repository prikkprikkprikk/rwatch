<?php

declare(strict_types=1);

namespace RWatch\Config;

use RWatch\Config\Exception\ConfigFileReadException;

class ConfigFile {

    protected string $fileContents = '';

    /**
     * @throws ConfigFileReadException
     */
    public function __construct(private ConfigFilePath $configFilePath) {
        if (!$configFilePath->fileIsReadable()) {
            throw new ConfigFileReadException(sprintf("Config file '%s' is not readable", $configFilePath->fullPath()));
        }

        $this->readFileContents();
    }

    public function getContents(): string {
        return $this->fileContents;
    }

    /**
     * Read the contents of the file at the given config file path.
     * Throws an exception if file_get_contents fails and returns false.
     *
     * @return void
     * @throws ConfigFileReadException
     */
    protected function readFileContents(): void {
        $fileContents = file_get_contents($this->configFilePath->fullPath());
        if (false === $fileContents) {
            throw new ConfigFileReadException(sprintf("Could not read config file '%s'", $this->configFilePath->fullPath()));
        }
        $this->fileContents = $fileContents;
    }
}
