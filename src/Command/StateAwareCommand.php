<?php

declare(strict_types=1);

namespace RWatch\Command;

use RWatch\App\Contracts\AppStateInterface;
use RWatch\Command\Contracts\CommandInterface;

abstract class StateAwareCommand implements CommandInterface {

    /**
     * Class constructor takes an AppState object as an argument, so that
     * the app state can be read and/or updated by the command.
     *
     * @param AppStateInterface $appState The app's configuration object.
     */
    public function __construct(protected AppStateInterface $appState) {
    }
}