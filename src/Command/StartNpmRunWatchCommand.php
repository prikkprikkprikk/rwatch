<?php

declare(strict_types=1);

namespace RWatch\Command;

use RWatch\Command\Contracts\CommandInterface;
use RWatch\Config\ConfigInterface;
use RWatch\IO\IOInterface;
use RWatch\Shell\Enum\ExitCodes;
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
            $confirmation = $io->confirm("Prosjekt ikke valgt. Vennligst velg et prosjekt først.");

            if ($confirmation == false) {
                return null;
            }

            return new FetchSymlinksFromServerCommand($this->config);
        }

        // Using SSH with pseudo-terminal allocation (-t) and cd -P to resolve symlinks
        $sshStartCommand = sprintf(
            'ssh -t %s@%s "cd -P ~/%s && pwd && npm run watch"',
            $this->config->getUsername(),
            $this->config->getServer(),
            $project
        );

        $shellExitCode = $this->shellExecutor->execute($sshStartCommand);

        if ($shellExitCode == ExitCodes::SSH_CONNECTION_CLOSED) {
            return new FetchSymlinksFromServerCommand($this->config);
        }

        return new PauseCommand(
            message: "Kunne ikke kjøre 'npm run watch'. Resultatkode: " . $shellExitCode->value
                . PHP_EOL . "Vennligst prøv igjen. (Trykk ENTER for å fortsette.)",
            nextCommand: new FetchSymlinksFromServerCommand($this->config)
        );
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