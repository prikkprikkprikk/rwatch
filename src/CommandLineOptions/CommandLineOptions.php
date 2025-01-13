<?php
declare(strict_types=1);

namespace Dwatch\CommandLineOptions;

use Symfony\Component\Console\Exception\InvalidOptionException;

class CommandLineOptions implements CommandLineOptionsInterface {

    private static ?self $instance = null;

    /** @var array<string, mixed> */
    protected array $options = [];

    /**
     * @param array<string, string|null> $optionsWithPatterns
     */
    private function __construct(
        private readonly array $optionsWithPatterns = []
    ) {}

    /**
     * @param array<string, string|null> $optionsWithPatterns
     */
    public static function getInstance(array $optionsWithPatterns = []): CommandLineOptionsInterface {
        if (self::$instance === null) {
            self::$instance = new self($optionsWithPatterns);
        }
        $options = array_map(callback: function ($option) {
            return "$option:";
        }, array: array_keys(self::$instance->optionsWithPatterns));
        self::$instance->validateOptions();
        self::$instance->options = getopt(short_options: '', long_options: $options);
        return self::$instance;
    }

    /**
     * @return string|null
     */
    public function getOption(string $option): ?string {
        return $this->options[$option] ?? null;
    }

    public function validateOptions(): void {
        foreach ($this->options as $option => $value) {
            $pattern = $this->optionsWithPatterns[$option] ?? null;
            if ($pattern && !preg_match($pattern, $value)) {
                throw new InvalidOptionException(
                    sprintf("Invalid value for option '%s': %s", $option, $value)
                );
            }
        }
    }
}