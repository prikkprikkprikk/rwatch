<?php

declare(strict_types=1);

namespace RWatch\Config;

use RWatch\Config\Exception\ConfigFileReadException;
use RWatch\Container\Container;
use RWatch\Filesystem\Contracts\FilesystemInterface;

class ConfigFile {

    protected string $fileContents = '';
    protected FilesystemInterface $filesystem;

    /**
     * @param ConfigFilePath $configFilePath
     * @throws ConfigFileReadException
     */
    public function __construct(protected ConfigFilePath $configFilePath = new ConfigFilePath()) {
        $this->filesystem = Container::singleton(FilesystemInterface::class);
        if ($this->filesystem->isReadable($this->configFilePath->fullPath()) === false) {
            throw new ConfigFileReadException(sprintf("Config file '%s' is not readable", $this->configFilePath->fullPath()));
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
        $fileContents = $this->filesystem->fileGetContents($this->configFilePath->fullPath());
        if (false === $fileContents) {
            throw new ConfigFileReadException(sprintf("Could not read config file '%s'", $this->configFilePath->fullPath()));
        }
        $this->fileContents = $fileContents;
    }
}
