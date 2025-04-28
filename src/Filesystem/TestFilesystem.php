<?php

declare(strict_types=1);

namespace RWatch\Filesystem;

use RWatch\Filesystem\Contracts\FilesystemInterface;

/**
 * A test double for the Filesystem class that allows overriding filesystem operations.
 *
 * This class selectively overrides filesystem operations (like exists(), isFile(), isDirectory())
 * while preserving the behavior of utility methods (like join()). It enables testing
 * filesystem-dependent code without relying on the actual filesystem state.
 */
class TestFilesystem extends Filesystem implements FilesystemInterface {

    /**
     * @var array<string, array{
     *     isDirectory?: bool,
     *     isFile?: bool,
     *     exists?: bool,
     *     contents?: string,
     *     isReadable?: bool,
     * }> $fileConfig
     */
    protected array $fileConfig;

    public function __construct() {
        parent::__construct();
    }

    /**
     * @param string $fullFilePath Given this path, which values should the filesystem respond with?
     * @param array{
     *     isDirectory?: bool,
     *     isFile?: bool,
     *     exists?: bool,
     *     contents?: string,
     *     isReadable?: bool,
     *  } $cannedReturnValues
     * @return void
     */
    public function setFileConfig(string $fullFilePath, array $cannedReturnValues): void {
        $this->fileConfig[$fullFilePath] = $cannedReturnValues;
    }

    #[\Override]
    public function isDirectory(string $path): bool {
        return $this->fileConfig[$path]['isDirectory'] ?? false;
    }

    #[\Override]
    public function isFile(string $path): bool {
        return $this->fileConfig[$path]['isFile'] ?? false;
    }

    #[\Override]
    public function exists(string $path): bool {
        return $this->fileConfig[$path]['exists'] ?? false;
    }

    #[\Override]
    public function fileGetContents(string $path): string|false {
        return $this->fileConfig[$path]['contents'] ?? false;
    }

    #[\Override]
    public function isReadable(string $path): bool {
        return $this->fileConfig[$path]['isReadable'] ?? false;
    }
}