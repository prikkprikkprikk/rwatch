<?php

declare(strict_types=1);

namespace RWatch\AppFlow;
use RWatch\App\App;
use RWatch\AppFlow\Contracts\FlowStepInterface;
use RWatch\Container\Container;
use RWatch\IO\IOInterface;

class CreateConfigFilePromptFlowStep implements FlowStepInterface {

    /**
     * @inheritDoc
     */
    public function execute(): ?FlowStepInterface {

        $io = Container::singleton(IOInterface::class);

        $reply = $io->confirm('Create config file? (y/n)');

        if ($reply) {
            return new AskForServerNameFlowStep();
        }

        return null;
    }
}
