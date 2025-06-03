<?php

declare(strict_types=1);

namespace RWatch\AppFlow;

use RWatch\AppFlow\Contracts\FlowStepInterface;

class CheckCommandLineArgumentsFlowStep implements FlowStepInterface {

    public function execute(): ?FlowStepInterface {
        return null;
    }
}
