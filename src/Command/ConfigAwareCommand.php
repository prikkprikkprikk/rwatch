<?php

declare(strict_types=1);

namespace RWatch\Command;

use RWatch\Command\CommandInterface;
use RWatch\Config\ConfigInterface;
use RWatch\IO\IOInterface;

abstract class ConfigAwareCommand implements CommandInterface {

    /**
     * Class constructor takes a config object as an argument, so that
     * the app state can be read and/or updated by the command.
     *
     * @param ConfigInterface $config The app's configuration object.
     */
    public function __construct(protected ConfigInterface $config) {
        $this->config = $config;
    }
}