<?php

namespace Core\Infrastructure\Policies;

use App\Models\User;
use Core\Infrastructure\Persistence\Models\Point;

class PointPolicy
{
    /**
     * Determinar se o usuário pode visualizar um ponto específico.
     */
    public function view(User $user, Point $point): bool
    {
        return $user->isAdmin() || $user->id === $point->id;
    }

    /**
     * Determinar se o usuário pode listar pontos.
     */
    public function list(User $user, ?int $userId = null): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $userId === null || $user->id === $userId;
    }
}
