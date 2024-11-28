<?php

namespace Core\Application\Queries\Users;

use Core\Application\Contracts\Query;

class ListEmployeesQuery implements Query
{
    public function __construct(public readonly array $filters = []) {}
}
