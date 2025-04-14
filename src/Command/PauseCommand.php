<?php

declare(strict_types=1);

namespace RWatch\Command;

use RWatch\Command\Contracts\CommandInterface;
use RWatch\IO\IOInterface;

class PauseCommand implements CommandInterface {

    public function __construct(
        protected string $message,
        protected ?CommandInterface $nextCommand
    ) { }

    /**
     * @inheritDoc
     */
    public function execute(IOInterface $io): ?CommandInterface {
        $io->pause($this->message);

        return $this->nextCommand;
    }
}