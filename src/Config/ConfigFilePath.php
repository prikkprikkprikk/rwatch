<?php
declare(strict_types=1);

namespace RWatch\Config;

use Symfony\Component\Filesystem\Path;
use Symfony\Component\Filesystem\Filesystem;
use RWatch\Config\Exception\WrongFileFormatException;

/**
 * Class ConfigFilePath
 *
 * This class is responsible for dependency injection of the config file path into the Config class,
 * so that we can easily change the location of the config file without modifying the Config class,
 * both for testing and for supplying the config file path as a command line argument.
 *
 * It should be able to be constructed with a config file path, which can be either an absolute path
 * or a home-relative path, and optionally a filename.
 *
 * The constructor should take the directory and file name as separate parameters, and construct the full path.
 * Alternatively, it should take the full path as a single parameter and split it into the directory and file name.
 *
 * The class should be able to return:
 * - The full path of the config file
 * - The directory of the config file
 * - The name of the config file
 *
 * It should also validate the config file path, confirming that it exists and is a JSON file.
 */
class ConfigFilePath
{
    public const string DEFAULT_FILENAME = 'config.json';
    public const string DEFAULT_DIRECTORY = '~/.config/rwatch';

    private(set) string $directory;
    private(set) ?string $filename = null;

    private Filesystem $filesystem;

    public static function getDefaultConfigFilePath(): self
    {
        return new self(self::DEFAULT_DIRECTORY, self::DEFAULT_FILENAME);
    }

    public function __construct( string $path, ?string $filename = null )
    {
        $this->filesystem = new Filesystem();

        $path = $this->expandHomeDirectory($path);

        if ($filename == null) {
            $this->directory = Path::getDirectory($path);
            $this->filename = basename($path);
        } else {
            $this->directory = $path;
            $this->filename = $filename;
        }

        $this->validatePath();
    }

    /**
     * Validates the path.
     *
     * @return void
     * @throws WrongFileFormatException
     */
    private function validatePath(): void
    {
        if (!$this->isJsonFile()) {
            throw new WrongFileFormatException('Config file is not a JSON file');
        }
        if ($this->fileExists()) {
            $directory = realpath($this->directory);
            if ($directory === false) {
                throw new \RuntimeException('Could not determine real path of directory');
            }
            $this->directory = $directory;
        }
    }

    /**
     * Checks whether the path is a JSON file.
     *
     * @return bool
     */
    private function isJsonFile(): bool
    {
        return Path::getExtension($this->fullPath()) === 'json';
    }

    /**
     * Returns the full path of the config file.
     *
     * @return string
     */
    public function fullPath(): string
    {
        return Path::normalize($this->directory . '/' . $this->filename);
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
     * Expands the home directory in the path, if it exists.
     *
     * @param string $path
     * @return string
     * @throws \RuntimeException
     */
    private function expandHomeDirectory(string $path): string
    {
        if (Path::isAbsolute($path)) {
            return $path;
        }
        if (str_starts_with($path, '~/')) {
            $homeDirectory = getenv('HOME');
            if ($homeDirectory === false) {
                throw new \RuntimeException('Could not determine home directory');
            }
            return Path::join($homeDirectory, substr($path, 2));
        }
        return $path;
    }

    /**
     * Whether the config file is readable.
     *
     * @return bool
     */
    public function fileIsReadable(): bool
    {
        return $this->fileExists()
            && is_readable($this->directory);
    }

    /**
     * Whether the directory is writable.
     *
     * @return bool
     */
    public function directoryIsWritable(): bool
    {
        return $this->filesystem->exists($this->directory)
            && is_writable($this->directory);
    }
}