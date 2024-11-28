<?php

namespace Core\Infrastructure\Persistence\Repositories;

use App\Models\User;
use Core\Domain\Entities\UserEntity;
use Core\Domain\Repositories\UserRepositoryInterface;
use Core\Domain\ValueObjects\Address;
use Core\Domain\ValueObjects\CPF;

class UserRepository implements UserRepositoryInterface
{
    public function save(UserEntity $user): UserEntity
    {
        $model = $user->getId() ? User::find($user->getId()) : new User();

        $model->name = $user->getName();
        $model->cpf = (string)$user->getCpf();
        $model->email = $user->getEmail();
        $model->password = $user->getPassword();
        $model->role = $user->getRole();
        $model->birth_date = $user->getBirthDate()->format('Y-m-d');

        $addressArray = $user->getAddress()->toArray();

        $model->cep = $addressArray['cep'];
        $model->street = $addressArray['street'];
        $model->city = $addressArray['city'];
        $model->state = $addressArray['state'];
        $model->number = $addressArray['number'];
        $model->address = $addressArray['street'] . ' ' . $addressArray['number'];

        if ($user->getPassword() !== $model->password) {
            $model->password = $user->getPassword();
        }

        $model->save();

        return $this->mapToEntity($model);
    }

    public function findById(int $id): ?UserEntity
    {
        $model = User::find($id);

        return $model ? $this->mapToEntity($model) : null;
    }

    public function findByCPF(CPF $cpf): ?UserEntity
    {
        $model = User::where('cpf', (string)$cpf)->first();

        return $model ? $this->mapToEntity($model) : null;
    }

    public function cpfExists(CPF $cpf): bool
    {
        return User::where('cpf', (string)$cpf)->exists();
    }

    public function delete(UserEntity $user): void
    {
        $model = User::find($user->getId());

        if ($model) {
            $model->delete();
        }
    }

    public function findByFilters(array $filters): array
    {
        $query = User::query()->where('role', 'employee');

        if (isset($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (isset($filters['cpf'])) {
            $query->where('cpf', $filters['cpf']);
        }

        if (isset($filters['email'])) {
            $query->where('email', 'like', '%' . $filters['email'] . '%');
        }

        if (isset($filters['birth_date'])) {
            $query->whereDate('birth_date', $filters['birth_date']);
        }

        return $query->get()->toArray();
    }


    private function mapToEntity(User $model): UserEntity
    {
        $address = new Address(
            cep: $model->cep,
            street: $model->street,
            number: $model->number ?? 0,
            city: $model->city,
            state: $model->state
        );

        return new UserEntity(
            name: $model->name,
            cpf: new CPF($model->cpf),
            email: $model->email,
            password: $model->password,
            role: $model->role,
            birthDate: new \DateTimeImmutable($model->birth_date),
            address: $address,
            id: $model->id
        );
    }
}
