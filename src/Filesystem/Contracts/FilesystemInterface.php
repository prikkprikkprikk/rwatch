<?php

declare(strict_types=1);

namespace RWatch\Filesystem\Contracts;

interface FilesystemInterface {
    public function isDirectory(string $path): bool;
    public function isFile(string $path): bool;
    public function getDirectory(string $path): string;
    public function getExtension(string $path): string;
    public function normalize(string $path): string;
    public function exists(string $path): bool;
    public function isAbsolute(string $path): bool;
    public function join(string ...$paths): string;
    public function fileGetContents(string $path): string|false;
    public function isReadable(string $path): bool;
}
