<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Repository\UsuarioRepository;
use App\Service\GenericProviderSingleton;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, Security $security, UsuarioRepository $usuarioRepository, EntityManagerInterface $entityManager): void
    {
        // // get the login error if there is one
        // $error = $authenticationUtils->getLastAuthenticationError();

        // // last username entered by the user
        // $lastUsername = $authenticationUtils->getLastUsername();

        // return $this->render('login/login.html.twig', [
        //     'last_username' => $lastUsername,
        //     'error' => $error,
        // ]);

        $provider = GenericProviderSingleton::getInstance()->getProvider();

        // If we don't have an authorization code then get one
        if (!isset($_GET['code'])) {

            // Limpar sessões
            session_start();
            session_unset();
            session_destroy();

            $authorizationUrl = $provider->getAuthorizationUrl([
                'scope' => ['openid'] // Para o método getResourceOwner() funcionar, precisa incluir o openid no scope.
            ]);

            // Redirect the user to the authorization URL.
            header('Location: ' . $authorizationUrl);
            exit;

        } else {

            try {
        
                // Try to get an access token using the authorization code grant.
                $accessToken = $provider->getAccessToken('authorization_code', [
                    'code' => $_GET['code']
                ]);
        
                // // Using the access token, we may look up details about the
                // // resource owner.
                $resourceOwner = $provider->getResourceOwner($accessToken);
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
                } else {
                    //dd($usuario);
                }

                //$security->login($usuario, 'form_login');
                $security->login($usuario, 'form_login', 'main');

                dd($security->getUser());
                //echo 'usuário logado?';
                exit;

                // session_start();
                // $_SESSION['user_uuid'] = $arrayUsuarioLogado['sub'];
                // $_SESSION['user_full_name'] = $arrayUsuarioLogado['name'];
                // $_SESSION['user_username'] = $arrayUsuarioLogado['preferred_username'];
                // $_SESSION['user_email'] = $arrayUsuarioLogado['email'];
                // $_SESSION['accessToken'] = $accessToken;
        
                // // Redirect the user to the authorization URL.
                header('Location: /organizacao-militar');
                exit;
        
            } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
                // Failed to get the access token or user details.
                exit($e->getMessage());
            }
        
        }
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
