<?php

namespace App\Controller;

use App\Security\OAuth2Provider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends AbstractController
{
    #[Route(path: '/', name: 'app_index')]
    public function index(): Response
    {
        return $this->redirectToRoute('app_login');
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(OAuth2Provider $OAuth2Provider): Response
    {
        $provider = $OAuth2Provider->getProvider();

        // If we don't have an authorization code then get one
        if (!isset($_GET['code'])) {

            // Para o método getResourceOwner() funcionar, precisa incluir o openid no scope.
            $authorizationUrl = $provider->getAuthorizationUrl([
                'scope' => ['openid']
            ]);

            // Redirect the user to the authorization URL.
            return $this->redirect($authorizationUrl);

        }
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(Security $security): Response
    {
        // logout the user in on the current firewall
        $response = $security->logout(false);

        return $this->redirect($_ENV['URL_LOGOUT']);
    }
}
