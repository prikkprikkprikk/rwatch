<?php
declare(strict_types=1);

namespace Dwatch\CommandLineOptions {
    // We need to mock the function getopt() to test the CommandLineOptions class
    // without actually running the command line.
    // Mockery does not support mocking built-in functions, so we need to use this workaround.
    function getopt(string $options = "", array $longopts = null): array {
        return [
            'server' => 'localhost',
            'username' => 'testuser',
            'project' => 'testproject',
        ];
    }
}

namespace Tests\Unit {

    use Dwatch\CommandLineOptions\CommandLineOptionsInterface;
    use Dwatch\CommandLineOptions\CommandLineOptions;

    it('can get options from command line', function ()
    {
        $options = CommandLineOptions::getInstance([
            'server' => '/^[\w.-]+$/', // Allows letters, numbers, dots, and hyphens
            'username' => '/^[\w_-]+$/', // Allows letters, numbers, hyphens and underscores
            'project' => '/^[\w_-]+$/', // Allows letters, numbers, hyphens and underscores
        ]);

        expect($options)->toBeInstanceOf(CommandLineOptionsInterface::class);

        expect($options->getOption('server'))->toBe('localhost');
        expect($options->getOption('username'))->toBe('testuser');
        expect($options->getOption('project'))->toBe('testproject');
    });


    it('returns null if option is not set', function ()
    {
        $options = CommandLineOptions::getInstance([]);

        expect($options->getOption('wrongoption'))->toBeNull();
    });
}