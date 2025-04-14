<?php

declare(strict_types=1);

namespace RWatch\Command;

use RWatch\App\App;
use RWatch\Command\Contracts\CommandInterface;
use RWatch\IO\IOInterface;
use RWatch\Shell\ShellExecutor;
use function Laravel\Prompts\select;

class FetchSymlinksFromServerCommand extends StateAwareCommand {
    /**
     * @inheritDoc
     */
    public function execute(IOInterface $io): ?CommandInterface {

        // TODO: Refactor this; it's just copied in from the old App->run() function.
        // Should probably be separated into several commands.

        // Get symlinks from remote server
        $command = sprintf(
            'ssh %s@%s "ls -F | grep @ | sed \'s/@//\'"',
            App::getState()->getUsername(),
            App::getState()->getServer()
        );
        $output = shell_exec($command);

        if (!$output) {
            $io->echo("Klarte ikke å hente symlinker fra serveren" . PHP_EOL);
            return null;
        }

        // Parse the output into array and clean up paths
        $projects = array_filter(explode("\n", trim($output)));
        $projects = array_map(function($path): string {
            // Remove './' from the beginning of the path
            return preg_replace('/^\.\//', '', $path) ?? '';
        }, $projects);

        if (empty($projects)) {
            $io->echo("Fant ingen symlinker på serveren" . PHP_EOL);
            return null;
        }

        // Add quit option
        $quitLabel = '❌ Avslutt';
        $projects[] = $quitLabel;

        // Show interactive selection
        $selectedProject = (string) select(
            label: "Velg prosjekt for kjøring av 'npm run watch' på ". App::getState()->getServer() .":",
            options: $projects,
            scroll: 10,
        );

        if ($selectedProject === $quitLabel) {
            $io->echo("Avslutter ..." . PHP_EOL);
            return null;
        }

        $this->appState->setProject($selectedProject);

        return new StartNpmRunWatchCommand(
            $this->appState,
            new ShellExecutor()
        );
    }
}