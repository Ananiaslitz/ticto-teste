<?php

use Core\Application\Commands\DeletePointCommand;
use Core\Application\Commands\RegisterPointCommand;
use Core\Application\Commands\UpdatePointCommand;
use Core\Application\Commands\Users\ChangePasswordCommand;
use Core\Application\Commands\Users\DeleteEmployeeCommand;
use Core\Application\Commands\Users\RegisterUserCommand;
use Core\Application\Commands\Users\UpdateEmployeeCommand;
use Core\Application\Handlers\DeletePointHandler;
use Core\Application\Handlers\RegisterPointHandler;
use Core\Application\Handlers\UpdatePointHandler;
use Core\Application\Handlers\Users\ChangePasswordHandler;
use Core\Application\Handlers\Users\DeleteEmployeeHandler;
use Core\Application\Handlers\Users\RegisterUserHandler;
use Core\Application\Handlers\Users\UpdateEmployeeHandler;

return [
    RegisterPointCommand::class => RegisterPointHandler::class,
    UpdatePointCommand::class => UpdatePointHandler::class,
    DeletePointCommand::class => DeletePointHandler::class,

    //Users
    RegisterUserCommand::class => RegisterUserHandler::class,
    ChangePasswordCommand::class => ChangePasswordHandler::class,
    UpdateEmployeeCommand::class => UpdateEmployeeHandler::class,
    DeleteEmployeeCommand::class => DeleteEmployeeHandler::class,
];
