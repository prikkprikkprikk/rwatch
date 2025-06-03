<?php

declare(strict_types=1);

namespace RWatch\App;

use RWatch\AppFlow\LaunchAppFlowStep;

class App {
    public function run(): void {
        $flowStep = new LaunchAppFlowStep();

        do {
            $flowStep = $flowStep->execute();
        } while ($flowStep);

        exit;
    }
}
