<?php
declare(strict_types=1);

namespace RWatch\CommandLineOptions {
    // We need to mock the function getopt() to test the CommandLineOptions class
    // without actually running the command line.
    // Mockery does not support mocking built-in functions, so we need to use this workaround.
    /**
     * @param string|null $short_options
     * @param array<string, string>|null $long_options
     * @return string[]
     */
    function getopt(?string $short_options = "", ?array $long_options = null): array {
        return [
            'server' => 'localhost',
            'username' => 'testUser',
            'project' => 'testProject',
        ];
    }
}

namespace Tests\Unit\CommandLineOptions {

    use RWatch\CommandLineOptions\CommandLineOptionsInterface;
    use RWatch\CommandLineOptions\CommandLineOptions;

    it('can get options from command line', function (): void
    {
        $options = new CommandLineOptions([
            'server' => '/^[\w.-]+$/', // Allows letters, numbers, dots, and hyphens
            'username' => '/^[\w_-]+$/', // Allows letters, numbers, hyphens and underscores
            'project' => '/^[\w_-]+$/', // Allows letters, numbers, hyphens and underscores
        ]);

        expect($options)->toBeInstanceOf(CommandLineOptionsInterface::class)
            ->and($options->getOption('server'))->toBe('localhost')
            ->and($options->getOption('username'))->toBe('testUser')
            ->and($options->getOption('project'))->toBe('testProject');
    });


    it('returns null if option is not set', function (): void
    {
        $options = new CommandLineOptions([]);

        expect($options->getOption('wrongOption'))->toBeNull();
    });
}