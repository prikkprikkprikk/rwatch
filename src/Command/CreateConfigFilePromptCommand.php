<?php

declare(strict_types=1);

namespace RWatch\Command;
use RWatch\App\App;
use RWatch\Command\Contracts\CommandInterface;
use RWatch\Container\Container;
use RWatch\IO\IOInterface;

class CreateConfigFilePromptCommand implements CommandInterface {

    /**
     * @inheritDoc
     */
    public function execute(): ?CommandInterface {

        $io = Container::singleton(IOInterface::class);

        $reply = $io->confirm('Create config file? (y/n)');

        if ($reply) {
            return new AskForServerNameCommand();
        }

        return null;
    }
}