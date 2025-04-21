<?php

declare(strict_types=1);

use RWatch\App\AppState;

it('', function () {
    $appState = AppState::getInstance();

    $command = new RWatch\Command\FetchSymlinksFromServerCommand($appState);

})->skip(message: "Not implemented yet");
