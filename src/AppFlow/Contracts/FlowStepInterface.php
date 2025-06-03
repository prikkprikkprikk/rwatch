<?php

declare(strict_types=1);

namespace RWatch\AppFlow\Contracts;

interface FlowStepInterface {
    /**
     * Execute the flow step, and return the next command to be executed,
     * or null if the program should exit.
     *
     * @return FlowStepInterface|null
     */
    public function execute(): ?FlowStepInterface;
}
