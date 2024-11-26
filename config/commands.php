<?php

use Core\Application\Commands\RegisterPointCommand;
use Core\Application\Handlers\RegisterPointHandler;

return [
    RegisterPointCommand::class => RegisterPointHandler::class,
];
