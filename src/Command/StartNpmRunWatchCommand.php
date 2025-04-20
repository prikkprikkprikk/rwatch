<?php

declare(strict_types=1);

namespace RWatch\Command;

use RWatch\App\AppState;
use RWatch\App\Contracts\AppStateInterface;
use RWatch\Command\Contracts\CommandInterface;
use RWatch\Config\ConfigInterface;
use RWatch\IO\IOInterface;
use RWatch\Shell\Enum\ExitCodes;
use RWatch\Shell\ShellExecutor;
use RWatch\Shell\ShellExecutorInterface;

class StartNpmRunWatchCommand implements CommandInterface{

    protected AppStateInterface $appState;
    protected ShellExecutorInterface $shellExecutor;

    /**
     * @param AppStateInterface|null $appState
     * @param ShellExecutorInterface|null $shellExecutor
     */
    public function __construct(
        ?AppStateInterface      $appState = null,
        ?ShellExecutorInterface $shellExecutor = null
    ) {
        $this->appState = $appState ?? AppState::getInstance();
        $this->shellExecutor = $shellExecutor ?? new ShellExecutor();
    }

    /**
     * @inheritDoc
     */
    public function execute(IOInterface $io): ?CommandInterface {

        $project = $this->appState->getProject();

        if (is_null($project)) {
            $confirmation = $io->confirm("Prosjekt ikke valgt. Vennligst velg et prosjekt først.");

            if ($confirmation == false) {
                return null;
            }

            return new FetchSymlinksFromServerCommand($this->appState);
        }

        // Using SSH with pseudo-terminal allocation (-t) and cd -P to resolve symlinks
        $sshStartCommand = sprintf(
            'ssh -t %s@%s "cd -P ~/%s && pwd && npm run watch"',
            $this->appState->getUsername(),
            $this->appState->getServer(),
            $project
        );

        $shellExitCode = $this->shellExecutor->execute($sshStartCommand);

        if ($shellExitCode == ExitCodes::SSH_CONNECTION_CLOSED) {
            return new FetchSymlinksFromServerCommand($this->appState);
        }

        return new PauseCommand(
            message: "Kunne ikke kjøre 'npm run watch'. Resultatkode: " . $shellExitCode->value
                . PHP_EOL . "Vennligst prøv igjen. (Trykk ENTER for å fortsette.)",
            nextCommand: new FetchSymlinksFromServerCommand($this->appState)
        );
    }

    protected function composeCommand(string $command): string {
        return sprintf(
            'ssh -t %s@%s "cd -P ~/%s && pwd && npm run watch"',
            $this->appState->getUsername(),
            $this->appState->getServer(),
            $command
        );
    }
}