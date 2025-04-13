<?php

declare(strict_types=1);

namespace RWatch\IO;

use RWatch\Screen\Screen;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\pause;

class ConsoleIO implements IOInterface {
    protected Screen $screen;

    public function __construct() {
        $this->screen = new Screen();
    }

    /**
     * @inheritDoc
     */
    public function ask(string $question): string {
        // TODO: Implement ask() method.
        return '';
    }

    /**
     * @inheritDoc
     */
    public function select(string $question, array $choices): string {
        // TODO: Implement select() method.
        return '';
    }

    /**
     * @inheritDoc
     */
    public function confirm(string $question): bool {
        $this->screen->enter();
        $confirmationValue = confirm($question);
        $this->screen->exit();
        return $confirmationValue;
    }

    /**
     * Pause execution with a message, require ENTER to be pressed to continue.
     *
     * @param string $message
     * @return null
     */
    public function pause(string $message): null {
        pause($message);
        return null;
    }

    /**
     * Simply echo the message without pausing.
     *
     * @param string $string
     * @return null
     */
    public function echo(string $string): null {
        echo($string);
        return null;
    }
}