<?php

declare(strict_types=1);

namespace RWatch\AppFlow;

use RWatch\AppFlow\Contracts\FlowStepInterface;

class CheckConfigFileOptionFlowStep implements FlowStepInterface {

    /**
     * As a first step in starting the app, check if there is any command line argument
     * for an alternative config file.
     *
     * @inheritDoc
     */
    public function execute(): ?FlowStepInterface {
        return null;
    }
}
