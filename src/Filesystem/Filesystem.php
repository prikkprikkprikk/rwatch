<?php

declare(strict_types=1);

namespace RWatch\Filesystem;

use RWatch\Filesystem\Contracts\FilesystemInterface;
use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;
use Symfony\Component\Filesystem\Path;

class Filesystem implements FilesystemInterface {

    protected SymfonyFilesystem $filesystem;

    public function __construct() {
        $this->filesystem = new SymfonyFilesystem();
    }

    public function isDirectory(string $path): bool {
        return is_dir($path);
    }

    public function isFile(string $path): bool {
        return is_file($path);
    }

    public function getDirectory(string $path): string {
        return Path::getDirectory($path);
    }

    public function getExtension(string $path): string {
        return Path::getExtension($path);
    }

    public function normalize(string $path): string {
        return Path::normalize($path);
    }

    public function exists(string $path): bool {
        return $this->filesystem->exists($path);
    }

    public function isAbsolute(string $path): bool {
        return Path::isAbsolute($path);
    }

    public function join(string ...$paths): string {
        return Path::join(...$paths);
    }

    public function fileGetContents(string $path): string|false {
        return file_get_contents($path);
    }

    public function isReadable(string $path): bool {
        return is_readable($path);
    }
}
