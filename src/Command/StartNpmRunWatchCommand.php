<?php

declare(strict_types=1);

namespace RWatch\Command;

use RWatch\App\App;
use RWatch\Command\ConfigAwareCommand;
use RWatch\Config\ConfigInterface;
use RWatch\IO\IOInterface;
use RWatch\Shell\ShellExecutorInterface;

class StartNpmRunWatchCommand implements CommandInterface{

    public function __construct(
        protected ConfigInterface $config,
        protected ShellExecutorInterface $shellExecutor
    ) { }

    /**
     * @inheritDoc
     */
    public function execute(IOInterface $io): ?CommandInterface {

        $project = $this->config->getProject();

        if (is_null($project)) {
            $confirmation = $io->confirm("No project selected. Please select a project first.");

            if ($confirmation == false) {
                return null;
            }

            return new FetchSymlinksFromServerCommand($this->config);
        }

        // Start npm watch on the remote server
        echo sprintf("Selected project: %s", $project) . PHP_EOL;

        // Using SSH with pseudo-terminal allocation (-t) and cd -P to resolve symlinks
        $sshStartCommand = sprintf(
            'ssh -t %s@%s "cd -P ~/%s && pwd && npm run watch"',
            $this->config->getUsername(),
            $this->config->getServer(),
            $project
        );

        $resultCode = $this->shellExecutor->execute($sshStartCommand);

        if ($resultCode != 0) {
            return null;
        }

        return new FetchSymlinksFromServerCommand($this->config);
    }

    protected function composeCommand(string $command): string {
        return sprintf(
            'ssh -t %s@%s "cd -P ~/%s && pwd && npm run watch"',
            $this->config->getUsername(),
            $this->config->getServer(),
            $command
        );
    }
}