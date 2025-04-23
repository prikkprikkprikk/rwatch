<?php

declare(strict_types=1);

namespace RWatch\Command;

use RWatch\App\AppState;
use RWatch\App\Contracts\AppStateInterface;
use RWatch\Command\Contracts\CommandInterface;
use RWatch\Config\ConfigInterface;
use RWatch\Container\Container;
use RWatch\IO\IOInterface;
use RWatch\Shell\Enum\ExitCodes;
use RWatch\Shell\ShellExecutor;
use RWatch\Shell\ShellExecutorInterface;

class StartNpmRunWatchCommand implements CommandInterface{

    /**
     * @inheritDoc
     */
    public function execute(): ?CommandInterface {

        $appState = Container::singleton(AppStateInterface::class);
        $project = $appState->getProject();

        $io = Container::singleton(IOInterface::class);

        if (is_null($project)) {
            $confirmation = $io->confirm("Prosjekt ikke valgt. Vennligst velg et prosjekt først.");

            if ($confirmation === false) {
                return null;
            }

            return new FetchSymlinksFromServerCommand();
        }

        // Using SSH with pseudo-terminal allocation (-t) and cd -P to resolve symlinks
        $sshStartCommand = sprintf(
            'ssh -t %s@%s "cd -P ~/%s && pwd && npm run watch"',
            $appState->getUsername(),
            $appState->getServer(),
            $project
        );

        $shellExecutor = Container::singleton(ShellExecutorInterface::class);
        $shellExitCode = $shellExecutor->execute($sshStartCommand);

        if ($shellExitCode == ExitCodes::SSH_CONNECTION_CLOSED) {
            return new FetchSymlinksFromServerCommand();
        }

        $message = "Kunne ikke kjøre 'npm run watch'. Resultatkode: " . $shellExitCode->value
            . PHP_EOL . "Vennligst prøv igjen. (Trykk ENTER for å fortsette.)";

        echo $message . PHP_EOL;

        return new PauseCommand(
            message: $message,
            nextCommand: new FetchSymlinksFromServerCommand()
        );
    }

    protected function composeCommand(string $command): string {
        $appState = Container::singleton(AppStateInterface::class);

        return sprintf(
            'ssh -t %s@%s "cd -P ~/%s && pwd && npm run watch"',
            $appState->getUsername(),
            $appState->getServer(),
            $command
        );
    }
}