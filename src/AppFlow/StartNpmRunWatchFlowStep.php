<?php

declare(strict_types=1);

namespace RWatch\AppFlow;

use RWatch\App\AppState;
use RWatch\App\Contracts\AppStateInterface;
use RWatch\AppFlow\Contracts\FlowStepInterface;
use RWatch\Config\ConfigInterface;
use RWatch\Container\Container;
use RWatch\IO\IOInterface;
use RWatch\Shell\Enum\ExitCodes;
use RWatch\Shell\ShellExecutor;
use RWatch\Shell\ShellExecutorInterface;

class StartNpmRunWatchFlowStep implements FlowStepInterface{

    /**
     * @inheritDoc
     */
    public function execute(): ?FlowStepInterface {

        $appState = Container::singleton(AppStateInterface::class);
        $project = $appState->getProject();

        $io = Container::singleton(IOInterface::class);

        if (is_null($project)) {
            $confirmation = $io->confirm("Prosjekt ikke valgt. Vennligst velg et prosjekt først.");

            if ($confirmation === false) {
                return null;
            }

            return new FetchSymlinksFromServerFlowStep();
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
            return new FetchSymlinksFromServerFlowStep();
        }

        $message = "Kunne ikke kjøre 'npm run watch'. Resultatkode: " . $shellExitCode->value
            . PHP_EOL . "Vennligst prøv igjen. (Trykk ENTER for å fortsette.)";

        echo $message . PHP_EOL;

        return new PauseFlowStep(
            message: $message,
            nextFlowStep: new FetchSymlinksFromServerFlowStep()
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
