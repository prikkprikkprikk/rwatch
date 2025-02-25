<?php

namespace RWatch\App;

use RWatch\CommandLineOptions\CommandLineOptions;
use RWatch\CommandLineOptions\CommandLineOptionsInterface;
use RWatch\Config\Config;
use RWatch\Config\ConfigFilePath;
use RWatch\Screen\Screen;
use function Laravel\Prompts\pause;
use function Laravel\Prompts\select;

class App {

    protected CommandLineOptionsInterface $options;
    protected Config $config;

    public function __construct() {
        $this->config = new Config(ConfigFilePath::getDefaultConfigFilePath());
    }

    public function run(): void {
        // For now, we only support supplying the server and username as arguments
        // Get options from command line
        /* @var array<string, string|null> $commandLineOptions */
        $commandLineOptions = [
            'server' => '/^[\w.-]+$/', // Allows letters, numbers, dots, and hyphens
            'username' => '/^[\w_-]+$/', // Allows letters, numbers, hyphens and underscores
            'project' => '/^[\w_-]+$/', // Allows letters, numbers, hyphens and underscores
        ];
        $this->options = CommandLineOptions::getInstance($commandLineOptions);
        $server = $this->options->getOption('server');
        $username = $this->options->getOption('username');

        if (!isset($server) && !isset($username)) {
            $screen = new Screen();
            $screen->enter();
            echo "Mangler argumentene --server og --username\n";
            // Prompt user to press enter to exit using Laravel Prompts
            pause('Trykk enter for å avslutte');
            $screen->exit();
            exit(0);
        }

        if (!isset($server) || !isset($username)) {
            if (!isset($server)) {
                echo "Mangler argumentet --server\n";
            }
            if (!isset($username)) {
                echo "Mangler argumentet --username\n";
            }
            exit(1);
        }

        $this->config->setServer($server);
        $this->config->setUsername($username);

        // Get symlinks from remote server
        $command = sprintf(
            'ssh %s@%s "ls -F | grep @ | sed \'s/@//\'"',
            $this->config->getUsername(),
            $this->config->getServer()
        );
        $output = shell_exec($command);

        if (!$output) {
            echo "Klarte ikke å hente symlinker fra serveren\n";
            exit(1);
        }

        // Parse the output into array and clean up paths
        $projects = array_filter(explode("\n", trim($output)));
        $projects = array_map(function($path): string {
            // Remove './' from the beginning of the path
            return preg_replace('/^\.\//', '', $path) ?? '';
        }, $projects);

        if (empty($projects)) {
            echo "Fant ingen symlinker på serveren\n";
            exit(1);
        }

        // Add quit option
        $quitLabel = '❌ Avslutt';
        $projects[] = $quitLabel;

        // Show interactive selection
        $selected = (string) select(
            label: "Velg prosjekt for kjøring av 'npm run watch' på ". $this->config->getServer() .":",
            options: $projects,
            scroll: 10,
        );

        if ($selected === $quitLabel) {
            echo "Avslutter ...\n";
            exit(0);
        }

        // Start npm watch on the remote server
        echo "Starter 'npm run watch' for $selected ...\n";

        // Using SSH with pseudo-terminal allocation (-t) and cd -P to resolve symlinks
        $sshStartCommand = sprintf(
            'ssh -t %s@%s "cd -P ~/%s && pwd && npm run watch"',
            $this->config->getUsername(),
            $this->config->getServer(),
            escapeshellarg($selected)
        );

        passthru($sshStartCommand);
    }
}