<?php

declare(strict_types=1);

namespace RWatch\IO;

interface IOInterface {

    /**
     * Ask a user to provide a text string.
     *
     * @param string $question
     * @return string
     */
    public function ask(string $question): string;

    /**
     * Ask a user to select an option from a list of choices.
     *
     * @param string $question
     * @param string[] $choices
     * @return string
     */
    public function select(string $question, array $choices): string;

    /**
     * Ask a user to confirm something.
     *
     * @param string $question
     * @return boolean
     */
    public function confirm(string $question): bool;

    /**
     * Pause execution with a message, require ENTER to be pressed to continue.
     *
     * @param string $message
     * @return null
     */
    public function pause(string $message): null;

    /**
     * Simply echo the message without pausing.
     *
     * @param string $string
     * @return null
     */
    public function echo(string $string): null;
}