<?php

namespace App\Tests\Core\Application\Handlers\Users;

use Core\Application\Commands\Users\ChangePasswordCommand;
use Core\Application\Handlers\Users\ChangePasswordHandler;
use Core\Domain\Entities\UserEntity;
use Core\Domain\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class ChangePasswordHandlerTest extends TestCase
{
    private $userRepositoryMock;
    private $changePasswordHandler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepositoryMock = $this->createMock(UserRepositoryInterface::class);
        $this->changePasswordHandler = new ChangePasswordHandler($this->userRepositoryMock);
    }

    public function testHandleSuccessfullyChangesPassword(): void
    {
        $command = new ChangePasswordCommand(
            userId: 1,
            currentPassword: 'oldPassword',
            newPassword: 'newPassword'
        );

        $userMock = $this->createMock(UserEntity::class);

        $this->userRepositoryMock
            ->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($userMock);

        $userMock
            ->expects($this->once())
            ->method('getPassword')
            ->willReturn(Hash::make('oldPassword'));

        $userMock
            ->expects($this->once())
            ->method('changePassword')
            ->with($this->callback(function ($hashedPassword) {
                return Hash::check('newPassword', $hashedPassword);
            }));

        $this->userRepositoryMock
            ->expects($this->once())
            ->method('save')
            ->with($userMock);

        $this->changePasswordHandler->handle($command);
    }

    public function testHandleThrowsExceptionWhenUserNotFound(): void
    {
        $command = new ChangePasswordCommand(
            userId: 1,
            currentPassword: 'oldPassword',
            newPassword: 'newPassword'
        );

        $this->userRepositoryMock
            ->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn(null);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Usuário não encontrado.');

        $this->changePasswordHandler->handle($command);
    }

    public function testHandleThrowsExceptionWhenCurrentPasswordIsIncorrect(): void
    {
        $command = new ChangePasswordCommand(
            userId: 1,
            currentPassword: 'wrongPassword',
            newPassword: 'newPassword'
        );

        $userMock = $this->createMock(UserEntity::class);

        $this->userRepositoryMock
            ->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($userMock);

        $userMock
            ->expects($this->once())
            ->method('getPassword')
            ->willReturn(Hash::make('correctPassword'));

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Senha atual está incorreta.');

        $this->changePasswordHandler->handle($command);
    }
}
