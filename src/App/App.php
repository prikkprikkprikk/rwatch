<?php

namespace Dwatch\App;

use Dwatch\Config\Config;
use function Laravel\Prompts\select;

class App {

    public function __construct(
        protected Config $config = new Config()
    ) {
    }

    public function run() {
        // For now, we only support supplying the server and username as arguments
        // Get options from command line
        $options =getopt('', ['server:', 'username:']);

        if (!isset($options['server']) || !isset($options['username'])) {
            if (!isset($options['server'])) {
                echo "Mangler argumentet --server\n";
            }
            if (!isset($options['username'])) {
                echo "Mangler argumentet --username\n";
            }
            exit(1);
        }

        $this->config->setServer($options['server']);
        $this->config->setUsername($options['username']);

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
        $options = array_filter(explode("\n", trim($output)));
        $options = array_map(function($path) {
            // Remove './' from the beginning of the path
            return preg_replace('/^\.\//', '', $path);
        }, $options);

        if (empty($options)) {
            echo "Fant ingen symlinker på serveren\n";
            exit(1);
        }

        // Add quit option
        $quitLabel = '❌ Avslutt';
        $options[] = $quitLabel;

        // Show interactive selection
        $selected = select(
            label: "Velg prosjekt for kjøring av 'npm run watch' på ". $this->config->getServer() .":",
            options: $options,
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