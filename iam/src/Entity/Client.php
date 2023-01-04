<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\ORM\Mapping as ORM;
use League\Bundle\OAuth2ServerBundle\Model\AbstractClient;

#[ORM\Entity()]
//#[ORM\Entity(repositoryClass: ClientRepository::class)]
#[ORM\Table('oauth2_client')]
class Client extends AbstractClient
{
    /**
     * @var string
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected $identifier;

    public function __construct(string $name, string $identifier, ?string $secret)
    {
        parent::__construct($name, $identifier, $secret);
    }
}
