<?php

declare(strict_types=1);

use RWatch\App\AppState;

it('', function () {
    $appState = new AppState();

    $command = new RWatch\Command\FetchSymlinksFromServerCommand($appState);

});
