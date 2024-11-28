<?php

namespace Core\Application\Commands\Users;

use Core\Application\Contracts\Command;

class DeleteEmployeeCommand implements Command
{
    public function __construct(public readonly int $id) {}
}
