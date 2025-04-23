<?php

namespace RWatch\App;

use RWatch\Command\LoadConfigFileCommand;

class App {

    public function __construct() {}

    public function run(): void {
        // $command = new CreateConfigFilePromptCommand();
        $command = new LoadConfigFileCommand();

        do {
            $command = $command->execute();
        } while ($command);

        exit;
    }
}