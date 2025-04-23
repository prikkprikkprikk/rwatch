<?php
declare(strict_types=1);

namespace RWatch\CommandLineOptions;

use Symfony\Component\Console\Exception\InvalidOptionException;

class CommandLineOptions implements CommandLineOptionsInterface {

    /** @var array<string, mixed> */
    protected array $options = [];

    /**
     * @param array<string, string|null> $optionsWithPatterns
     */
    public function __construct(
        private readonly array $optionsWithPatterns = []
    ) {
        $options = array_map(callback: function ($option) {
            return "$option:";
        }, array: array_keys($this->optionsWithPatterns));
        $this->validateOptions();
        $this->options = getopt(short_options: '', long_options: $options);
    }

    /**
     * @return string|null
     */
    public function getOption(string $option): ?string {
        $value = $this->options[$option] ?? null;
        return is_string($value) ? $value : null;
    }

    public function validateOptions(): void {
        foreach ($this->options as $option => $value) {
            $pattern = $this->optionsWithPatterns[$option] ?? null;
            if (!is_string($value)) {
                continue;
            }
            if ($pattern && !preg_match($pattern, $value)) {
                throw new InvalidOptionException(
                    sprintf("Invalid value for option '%s': %s", $option, $value)
                );
            }
        }
    }
}