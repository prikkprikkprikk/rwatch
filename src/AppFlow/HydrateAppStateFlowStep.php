<?php

declare(strict_types=1);

namespace RWatch\AppFlow;

use RWatch\App\Contracts\AppStateInterface;
use RWatch\AppFlow\Contracts\FlowStepInterface;
use RWatch\Config\Config;
use RWatch\Config\ConfigFile;
use RWatch\Container\Container;

class HydrateAppStateFlowStep implements FlowStepInterface {

    /**
     * @return FlowStepInterface|null
     */
    public function execute(): ?FlowStepInterface {
        return new FetchSymlinksFromServerFlowStep();
    }
}
