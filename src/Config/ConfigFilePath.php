<?php
declare(strict_types=1);

namespace RWatch\Config;

use RuntimeException;
use RWatch\Config\Exception\DirectoryNotFoundException;
use RWatch\Config\Exception\FileNotFoundException;
use RWatch\Container\Container;
use RWatch\Filesystem\Contracts\FilesystemInterface;
use RWatch\Config\Exception\WrongFileFormatException;

/**
 * Class ConfigFilePath
 *
 * This class is responsible for dependency injection of the config file path into the Config class,
 * so that we can easily change the location of the config file without modifying the Config class,
 * both for testing and for supplying the config file path as a command line argument.
 *
 * It should be able to be constructed with a config file path, which can be either an absolute path
 * or a home-relative path, and/or optionally a filename.
 *
 * The constructor should take the directory and file name as separate parameters, and construct the full path.
 * Alternatively, it should take the full path as a single parameter and split it into the directory and file name.
 *
 * The class should be able to return:
 * - The directory of the config file
 * - The name of the config file
 * - The full path of the config file
 *
 * It should also validate the config file path, confirming that it exists, is readable and is a JSON file.
 */
class ConfigFilePath
{
    public const string DEFAULT_FILENAME = 'config.json';
    public const string DEFAULT_DIRECTORY = '~/.config/rwatch';

    private(set) string $directory;
    private(set) ?string $filename = null;

    private FilesystemInterface $filesystem;

    /**
     * @return self
     */
    public static function getDefaultConfigFilePath(): self
    {
        return new self(
            path: self::DEFAULT_DIRECTORY,
            filename: self::DEFAULT_FILENAME
        );
    }

    /**
     *
     *
     * @param string|null $path
     * @param string|null $filename
     * @throws WrongFileFormatException
     * @throws DirectoryNotFoundException|FileNotFoundException
     */
    public function __construct( ?string $path = null, ?string $filename = null)
    {
        $this->filesystem = Container::singleton(FilesystemInterface::class);

        if ($path === null) {
            $this->directory = $this->expandHomeDirectory(self::DEFAULT_DIRECTORY);

            if ($filename === null) {
                $this->filename = self::DEFAULT_FILENAME;
            }
        } else {
            if ($filename === null) {
                $path = $this->expandHomeDirectory($path);
                if ($this->filesystem->isFile($path)) {
                    $this->directory = $this->filesystem->getDirectory($path);
                    $this->filename = basename($path);
                } else {
                    if ($this->filesystem->isDirectory($path) === false) {
                        throw new DirectoryNotFoundException("Directory does not exist: $path");
                    }
                    $this->directory = $path;
                    $this->filename = self::DEFAULT_FILENAME;
                }
            } else {
                $this->directory = $this->expandHomeDirectory($path);
                $this->filename = $filename;
            }
        }

        $this->validatePath();
    }

    /**
     * Validates the path. Throws a relevant error if any problem is encountered.
     *
     * @return void
     * @throws WrongFileFormatException
     * @throws RuntimeException
     * @throws FileNotFoundException
     */
    private function validatePath(): void
    {
        if ($this->fileExists() === false) {
            throw new FileNotFoundException(sprintf('Config file "%s" does not exist', $this->fullPath()));
        }
        if ($this->isJsonFile() === false) {
            throw new WrongFileFormatException('Config file is not a JSON file');
        }
        $realpath = realpath($this->directory);
        assert($realpath !== false);
        $this->directory = $realpath;
    }

    /**
     * Checks whether the path is a JSON file.
     *
     * @return bool
     */
    private function isJsonFile(): bool
    {
        return $this->filesystem->getExtension($this->fullPath()) === 'json';
    }

    /**
     * Returns the full path of the config file.
     *
     * @return string
     */
    public function fullPath(): string
    {
        return $this->filesystem->normalize($this->directory . '/' . $this->filename);
    }

    /**
     * Whether the directory exists.
     *
     * @return bool
     */
    public function directoryExists(): bool
    {
        return $this->filesystem->exists($this->directory);
    }

    /**
     * Whether the config file exists.
     *
     * @return bool
     */
    public function fileExists(): bool
    {
        return $this->filesystem->exists($this->fullPath());
    }

    /**
     * Expands the home directory in the directory, if it exists.
     *
     * @throws RuntimeException
     */
    private function expandHomeDirectory(string $path): string
    {
        if ($this->filesystem->isAbsolute($path)) {
            return $path;
        }
        if (str_starts_with($path, '~/')) {
            $homeDirectory = getenv('HOME');
            assert($homeDirectory !== false);
            return $this->filesystem->join($homeDirectory, substr($path, 2));
        }
        return $path;
    }
}