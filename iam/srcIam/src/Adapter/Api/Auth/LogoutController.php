<?php declare(strict_types=1);

namespace IdentityAccess\Adapter\Api\Auth;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LogoutController extends AbstractController
{
    #[Route('/logout/old', name: 'logout_old', methods: ['GET'])]
    public function logout()
    {
    }
}
