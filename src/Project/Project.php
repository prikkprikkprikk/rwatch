<?php

namespace RWatch\Project;

/**
 * Project class.
 *
 * Represents a project on the server and contains its name and path.
 *
 * The path can be either an absolute path or a home-relative path.
 *
 * If name is not provided, it will be inferred from the path.
 *
 * @property string|null $name
 * @property string $path
 */
class Project
{
    public function __construct(
        private string  $path,
        private ?string $name = null,
    ) {
        $this->validatePath($path);

        if (!$name) {
            $this->name = basename($path);
        }
    }

    public function getName(): string|null
    {
        return $this->name;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Validates the project path.
     *
     * The path must start with "/" or "~/".
     *
     * @param string $path
     * @return void
     * @throws \InvalidArgumentException
     */
    private function validatePath(string $path): void
    {
        if (!str_starts_with($path, '/') && !str_starts_with($path, '~/')) {
            throw new \InvalidArgumentException('Path must start with "/" or "~/"');
        }
    }
}
