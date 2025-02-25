<?php
declare(strict_types=1);

namespace RWatch\CommandLineOptions;

interface CommandLineOptionsInterface {
    public function getOption(string $option): ?string;
}