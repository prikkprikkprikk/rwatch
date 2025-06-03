<?php

declare(strict_types=1);

namespace RWatch\AppFlow;

use RWatch\App\App;
use RWatch\App\Contracts\AppStateInterface;
use RWatch\AppFlow\Contracts\FlowStepInterface;
use RWatch\Container\Container;
use RWatch\IO\IOInterface;
use RWatch\Shell\ShellExecutor;
use function Laravel\Prompts\select;

class FetchSymlinksFromServerFlowStep implements FlowStepInterface {
    /**
     * @inheritDoc
     */
    public function execute(): ?FlowStepInterface {

        $appState = Container::singleton(AppStateInterface::class);
        $io = Container::singleton(IOInterface::class);

        // TODO: Refactor this; it's just copied in from the old App->run() function.
        // Should probably be separated into several flow steps.

        // Get symlinks from remote server
        $command = sprintf(
            'ssh %s@%s "ls -F | grep @ | sed \'s/@//\'"',
            $appState->getUsername(),
            $appState->getServer()
        );
        $output = shell_exec($command);

        if (!$output) {
            $io->echo("Klarte ikke å hente symlinker fra serveren" . PHP_EOL);
            return null;
        }

        // Parse the output into array and clean up paths
        $projects = array_filter(explode("\n", trim($output)));
        $projects = array_map(fn($path): string =>
            // Remove './' from the beginning of the path
            preg_replace('/^\.\//', '', (string) $path) ?? '', $projects);

        if (empty($projects)) {
            $io->echo("Fant ingen symlinker på serveren" . PHP_EOL);
            return null;
        }

        // Add quit option
        $quitLabel = '❌ Avslutt';
        $projects[] = $quitLabel;

        // Show interactive selection
        $selectedProject = (string) select(
            label: "Velg prosjekt for kjøring av 'npm run watch' på ". $appState->getServer() .":",
            options: $projects,
            scroll: 10,
        );

        if ($selectedProject === $quitLabel) {
            $io->echo("Avslutter ..." . PHP_EOL);
            return null;
        }

        $appState->setProject($selectedProject);

        return new StartNpmRunWatchFlowStep();
    }
}
