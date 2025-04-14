<?php

declare(strict_types=1);

namespace RWatch\Shell\Enum;

enum ExitCodes: int {
    case SUCCESS = 0;
    case GENERIC_ERROR = 1;
    case SSH_CONNECTION_CLOSED = 255;

    public function isFailure(): bool {
        return $this->value > 0;
    }
}
