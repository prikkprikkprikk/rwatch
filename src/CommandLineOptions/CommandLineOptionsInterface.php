<?php
declare(strict_types=1);

namespace Dwatch\CommandLineOptions;

interface CommandLineOptionsInterface {
    public function getOption(string $option): ?string;
}