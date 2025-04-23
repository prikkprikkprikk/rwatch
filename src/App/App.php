<?php

declare(strict_types=1);

namespace RWatch\App;

use RWatch\Command\LaunchAppCommand;

class App {
    public function run(): void {
        $command = new LaunchAppCommand();

        do {
            $command = $command->execute();
        } while ($command);

        exit;
    }
}