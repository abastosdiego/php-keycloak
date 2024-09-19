<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Repository\UsuarioRepository;
use App\Service\GenericProviderSingleton;
use Doctrine\ORM\EntityManagerInterface;
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
    public function login(Security $security, UsuarioRepository $usuarioRepository, EntityManagerInterface $entityManager): Response
    {
        $provider = GenericProviderSingleton::getInstance()->getProvider();

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

    #[Route(path: '/dologin', name: 'app_do_login')]
    public function dologin(Security $security, UsuarioRepository $usuarioRepository, EntityManagerInterface $entityManager): Response
    {
        $provider = GenericProviderSingleton::getInstance()->getProvider();

        // Para o método getResourceOwner() funcionar, precisa incluir o openid no scope.
        $authorizationUrl = $provider->getAuthorizationUrl([
            'scope' => ['openid']
        ]);

        // If we don't have an authorization code then get one
        if (isset($_GET['code'])) {

            try {
        
                // Try to get an access token using the authorization code grant.
                $accessToken = $provider->getAccessToken('authorization_code', [
                    'code' => $_GET['code']
                ]);

                if (!$accessToken || $accessToken->hasExpired()) {
                    throw new \LogicException('Erro ao efetuar o login!(1)');
                }
                
                // // Using the access token, we may look up details about the
                // // resource owner.
                $resourceOwner = $provider->getResourceOwner($accessToken);

                if (!$resourceOwner) {
                    throw new \LogicException('Erro ao efetuar o login!(2)');
                }

                $arrayUsuarioLogado = $resourceOwner->toArray();
                //dd($arrayUsuarioLogado);

                $usuario = $usuarioRepository->findOneBy(['username' => $arrayUsuarioLogado['preferred_username']]);

                if(!$usuario){
                    $usuario = new Usuario();
                    $usuario->setUsername($arrayUsuarioLogado['preferred_username']);
                    $usuario->setRoles(array('ROLE_USER'));

                    // tell Doctrine you want to (eventually) save the Product (no queries yet)
                    $entityManager->persist($usuario);

                    // actually executes the queries (i.e. the INSERT query)
                    $entityManager->flush();
                }

                $security->login($usuario);

                return $this->redirectToRoute('app_organizacao_militar_index');
        
            } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
                // Failed to get the access token or user details.
                throw new \LogicException('Erro ao efetuar o login!(3)');
                //exit($e->getMessage());
            }
        
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
