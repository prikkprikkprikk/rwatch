<?php

declare(strict_types=1);

namespace RWatch\Command;

use RWatch\Command\Contracts\CommandInterface;
use RWatch\Config\ConfigInterface;

abstract class ConfigAwareCommand implements CommandInterface {

    /**
     * Class constructor takes a config object as an argument, so that
     * the app state can be read and/or updated by the command.
     *
     * @param ConfigInterface $config The app's configuration object.
     */
    public function __construct(protected ConfigInterface $config) {
    }
}