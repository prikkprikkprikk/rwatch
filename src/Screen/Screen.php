<?php

namespace RWatch\Screen;

class Screen
{
    public function enter(): void
    {
        // Switch to alternate buffer
        echo "\e[?1049h";

        // Optionally, clear the screen and move cursor to top-left
        echo "\e[2J";
        echo "\e[H";
    }

    public function exit(): void
    {
        // Switch back to main buffer
        echo "\e[?1049l";
    }
}
