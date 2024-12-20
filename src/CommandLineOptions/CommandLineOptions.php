<?php
declare(strict_types=1);

namespace Dwatch\CommandLineOptions;

use Symfony\Component\Console\Exception\InvalidOptionException;

class CommandLineOptions implements CommandLineOptionsInterface {

    private static ?self $instance = null;
    protected array $options = [];

    private function __construct(
        private readonly array $optionsWithPatterns = []
    ) {}

    public static function getInstance(array $optionsWithPatterns = []): CommandLineOptionsInterface {
        if (self::$instance === null) {
            self::$instance = new self($optionsWithPatterns);
        }
        $options = array_map(callback: function ($option) {
            return "$option:";
        }, array: array_keys(self::$instance->optionsWithPatterns));
        self::$instance->validateOptions();
        self::$instance->options = getopt('', $options);
        return self::$instance;
    }

    public function getOption(string $option) {
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