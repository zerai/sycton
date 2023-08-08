<?php declare(strict_types=1);

namespace IdentityAccess\Application\Model\Identity;

use Ecotone\Modelling\Attribute\AggregateIdentifier;
use Ecotone\Modelling\Attribute\CommandHandler;
use Ecotone\Modelling\Attribute\EventSourcingAggregate;
use Ecotone\Modelling\Attribute\EventSourcingHandler;
use Ecotone\Modelling\WithAggregateVersioning;
use IdentityAccess\Application\Model\Identity\Command\ChangeUserPassword;
use IdentityAccess\Application\Model\Identity\Command\RegisterUser;
use IdentityAccess\Application\Model\Identity\Event\RoleWasAssignedToUser;
use IdentityAccess\Application\Model\Identity\Event\UserPasswordWasChanged;
use IdentityAccess\Application\Model\Identity\Event\UserWasRegistered;

#[EventSourcingAggregate]
class User
{
    final public const REGISTER_USER = "user.registerUser";

    final public const CHANGE_PASSWORD = "user.changePassword";

    use WithAggregateVersioning;

    #[AggregateIdentifier]
    private string $userId;

    private string $email;

    private string $hashedPassword;

    private array $roles = [];

    #[CommandHandler(self::REGISTER_USER)]
    public static function register(RegisterUser $command): array
    {
        return [
            new UserWasRegistered($command->getUserId(), $command->getEmail(), $command->getHashedPassword()),
            new RoleWasAssignedToUser($command->getUserId(), 'ROLE_USER'),
        ];
    }

    #[CommandHandler(self::CHANGE_PASSWORD)]
    public function changePassword(ChangeUserPassword $command): array
    {
        return [new UserPasswordWasChanged($command->getUserId(), $command->getPassword())];
    }

    public function id(): string
    {
        return $this->userId;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function password(): string
    {
        return $this->hashedPassword;
    }

    public function roles(): array
    {
        return $this->roles;
    }

    #[EventSourcingHandler]
    public function applyUserWasRegistered(UserWasRegistered $event): void
    {
        $this->userId = $event->getUserId();
        $this->email = $event->getEmail();
        $this->hashedPassword = $event->getHashedPassword();
        $this->roles = ['ROLE_USER'];
    }

    #[EventSourcingHandler]
    public function applyUserPasswordWasChanged(UserPasswordWasChanged $event): void
    {
        $this->userId = $event->getUserId();
        $this->hashedPassword = $event->getPassword();
    }
}
