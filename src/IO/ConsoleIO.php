<?php

declare(strict_types=1);

namespace RWatch\IO;

use RWatch\Screen\Screen;
use function Laravel\Prompts\confirm;

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
}