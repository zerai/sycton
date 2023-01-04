<?php declare(strict_types=1);

namespace App\Repository;

use League\Bundle\OAuth2ServerBundle\Repository\ClientRepository as BaseClientRepository;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;

class ClientRepository implements ClientRepositoryInterface
{
    private BaseClientRepository $baseClientRepository;

    public function __construct(BaseClientRepository $baseClientRepository)
    {
        $this->baseClientRepository = $baseClientRepository;
    }

    public function getClientEntity($clientIdentifier)
    {
        return $this->baseClientRepository->getClientEntity($clientIdentifier);
    }

    public function validateClient($clientIdentifier, $clientSecret, $grantType): bool
    {
        return $this->baseClientRepository->validateClient($clientIdentifier, $clientSecret, $grantType);
    }
}
