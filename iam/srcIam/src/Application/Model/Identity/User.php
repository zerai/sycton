<?php declare(strict_types=1);

namespace IdentityAccess\Application\Model\Identity;

use Ecotone\Modelling\Attribute\AggregateIdentifier;
use Ecotone\Modelling\Attribute\CommandHandler;
use Ecotone\Modelling\Attribute\EventSourcingAggregate;
use Ecotone\Modelling\Attribute\EventSourcingHandler;
use Ecotone\Modelling\WithAggregateVersioning;
use IdentityAccess\Application\Model\Identity\Command\ChangeUserPassword;
use IdentityAccess\Application\Model\Identity\Command\RegisterUser;
use IdentityAccess\Application\Model\Identity\Event\UserPasswordWasChanged;
use IdentityAccess\Application\Model\Identity\Event\UserWasRegistered;

#[EventSourcingAggregate]
class User
{
    public const REGISTER_USER = "user.registerUser";

    public const CHANGE_PASSWORD = "user.changePassword";

    use WithAggregateVersioning;

    #[AggregateIdentifier]
    private string $userId;

    private string $email;

    private string $hashedPassword;

    #[CommandHandler(self::REGISTER_USER)]
    public static function register(RegisterUser $command): array
    {
        return [new UserWasRegistered($command->getUserId(), $command->getEmail(), $command->getHashedPassword())];
    }

    #[CommandHandler(self::CHANGE_PASSWORD)]
    public function changePassword(ChangeUserPassword $command): array
    {
        return [new UserPasswordWasChanged($command->getUserId(), $command->getPassword())];
    }

    #[EventSourcingHandler]
    public function applyUserWasRegistered(UserWasRegistered $event): void
    {
        $this->userId = $event->getUserId();
        $this->email = $event->getEmail();
        $this->hashedPassword = $event->getHashedPassword();
    }

    #[EventSourcingHandler]
    public function applyUserPasswordWasChanged(UserPasswordWasChanged $event): void
    {
        $this->userId = $event->getUserId();
        $this->hashedPassword = $event->getPassword();
    }
}
