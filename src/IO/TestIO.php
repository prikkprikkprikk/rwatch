<?php

declare(strict_types=1);

namespace RWatch\IO;

class TestIO implements IOInterface {

    /**
     * @param array<string, string|boolean> $cannedResponses
     */
    public function __construct(private array $cannedResponses = [])
    {
    }

    /**
     * Ask a user to provide a text string.
     *
     * @param string $question
     * @return string
     */
    public function ask(string $question): string {
        if (isset($this->cannedResponses[$question])) {
            return (string)$this->cannedResponses[$question];
        }
        return '';
    }

    /**
     * Ask a user to select an option from a list of choices.
     *
     * @param string $question
     * @param string[] $choices
     * @return string
     */
    public function select(string $question, array $choices): string {
        if (isset($this->cannedResponses[$question])) {
            return (string)$this->cannedResponses[$question];
        }
        return '';
    }

    /**
     * Ask a user to confirm something.
     *
     * @param string $question
     * @return boolean
     */
    public function confirm(string $question): bool {
        if (isset($this->cannedResponses[$question])) {
            return (bool)$this->cannedResponses[$question];
        }
        return false;
    }
}