<?php

namespace RWatch\App;

use RWatch\App\Contracts\AppStateInterface;
use RWatch\Command\FetchSymlinksFromServerCommand;
use RWatch\Command\LoadConfigFileCommand;
use RWatch\CommandLineOptions\CommandLineOptionsInterface;
use RWatch\Config\Config;
use RWatch\Config\ConfigInterface;
use RWatch\Filesystem\Contracts\FilesystemInterface;
use RWatch\Filesystem\Filesystem;
use RWatch\IO\ConsoleIO;
use RWatch\IO\IOInterface;

class App {

    protected CommandLineOptionsInterface $options;
    protected static AppStateInterface $state;
    protected static ConfigInterface $config;
    protected static IOInterface $io;
    protected static FilesystemInterface $filesystem;

    public function __construct(
        ?IOInterface $io = null,
        ?ConfigInterface $config = null,
        ?AppStateInterface $state = null,
        ?FilesystemInterface $filesystem = null
    ) {
        self::$io = $io ?? new ConsoleIO();

        self::$filesystem = $filesystem ?? new Filesystem();

        self::$config = $config ?? new Config();

        self::$state = $state ?? AppState::getInstance();
        self::$state->loadConfig(self::$config);
    }

    public static function getState(): AppStateInterface {
        self::$state ??= AppState::getInstance();
        self::$state->loadConfig(self::$config);
        return self::$state;
    }

    public function run(): void {
        // $command = new CreateConfigFilePromptCommand();
        $command = new LoadConfigFileCommand();

        do {
            $command = $command->execute(self::$io);
        } while ($command);

        exit;
    }
}