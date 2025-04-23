<?php

declare(strict_types=1);

namespace RWatch\Command;

use RWatch\Command\Contracts\CommandInterface;
use RWatch\Container\Container;
use RWatch\IO\IOInterface;

class PauseCommand implements CommandInterface {

    public function __construct(
        protected string $message,
        protected ?CommandInterface $nextCommand
    ) { }

    /**
     * @inheritDoc
     */
    public function execute(): ?CommandInterface {
        $io = Container::singleton(IOInterface::class);
        $io->pause($this->message);

        return $this->nextCommand;
    }
}