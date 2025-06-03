<?php

declare(strict_types=1);

namespace RWatch\AppFlow;

use RWatch\AppFlow\Contracts\FlowStepInterface;
use RWatch\Container\Container;
use RWatch\IO\IOInterface;

class PauseFlowStep implements FlowStepInterface {

    public function __construct(
        protected string             $message,
        protected ?FlowStepInterface $nextFlowStep
    ) { }

    /**
     * @inheritDoc
     */
    public function execute(): ?FlowStepInterface {
        $io = Container::singleton(IOInterface::class);
        $io->pause($this->message);

        return $this->nextFlowStep;
    }
}
