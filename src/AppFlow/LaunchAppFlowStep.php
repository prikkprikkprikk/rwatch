<?php

declare(strict_types=1);

namespace RWatch\AppFlow;

use RWatch\AppFlow\Contracts\FlowStepInterface;

class LaunchAppFlowStep implements Contracts\FlowStepInterface {

    /**
     * @inheritDoc
     */
    public function execute(): ?FlowStepInterface {
        return new LoadConfigFileFlowStep();
    }
}
