<?php

declare(strict_types=1);

namespace RWatch\Command;
use RWatch\App\App;
use RWatch\Command\Contracts\CommandInterface;
use RWatch\IO\IOInterface;

class CreateConfigFilePromptCommand implements CommandInterface {

    /**
     * @inheritDoc
     */
    public function execute(IOInterface $io): ?CommandInterface {

        $reply = $io->confirm('Create config file? (y/n)');

        if ($reply) {
            return new AskForServerNameCommand(App::getState());
        }

        return null;
    }
}