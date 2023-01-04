<?php declare(strict_types=1);

namespace App\Controller\Auth;

use Symfony\Component\Routing\Annotation\Route;

class OauthLogoutController
{
    /**
     * @throws \Exception
     */
    #[Route('/logout', name: 'app_logout', methods: ['GET'])]
    public function __invoke()
    {
        // controller can be blank: it will never be called!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }
}
