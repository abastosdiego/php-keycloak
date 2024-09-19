<?php

// src/Security/ApiKeyAuthenticator.php
namespace App\Security;

use App\Entity\Usuario;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class KeyCloakAuthenticator extends AbstractAuthenticator
{
    public function __construct(private UsuarioRepository $usuarioRepository, private EntityManagerInterface $entityManager, private LoggerInterface $logger)
    {
    }

    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning `false` will cause this authenticator
     * to be skipped.
     */
    public function supports(Request $request): ?bool
    {
        return (bool) $request->get('code');
    }

    public function authenticate(Request $request): Passport
    {
        $this->logger->info('########## Entrou no authenticate!');

        $code = $request->get('code');
        if (null === $code) {
            $this->logger->error('Erro no KeyCloakAuthenticator! $code é nulo!');
            throw new CustomUserMessageAuthenticationException('Erro ao efetuar o login!');
        }

        try {

            $provider = (new OAuth2Provider())->getProvider();
        
            // Try to get an access token using the authorization code grant.
            $accessToken = $provider->getAccessToken('authorization_code', [
                'code' => $code
            ]);

            if (!$accessToken || $accessToken->hasExpired()) {
                $this->logger->error('Erro no KeyCloakAuthenticator! $accessToken é nulo ou expirado!');
                throw new \LogicException('Erro ao efetuar o login!');
            }
            
            // // Using the access token, we may look up details about the
            // // resource owner.
            $resourceOwner = $provider->getResourceOwner($accessToken);

            if (!$resourceOwner) {
                $this->logger->error('Erro no KeyCloakAuthenticator! $resourceOwner é nulo!');
                throw new \LogicException('Erro ao efetuar o login!');
            }

            $usuarioLogadoKeyCloak = $resourceOwner->toArray();
            //dd($arrayUsuarioLogado);

            $usuario = $this->usuarioRepository->findOneBy(['username' => $usuarioLogadoKeyCloak['preferred_username']]);

            if(!$usuario){
                $usuario = new Usuario();
                $usuario->setUsername($usuarioLogadoKeyCloak['preferred_username']);
                $usuario->setRoles(array('ROLE_USER'));

                // tell Doctrine you want to (eventually) save the Product (no queries yet)
                $this->entityManager->persist($usuario);

                // actually executes the queries (i.e. the INSERT query)
                $this->entityManager->flush();
            }

            $this->logger->info("########## Login do usuário '" . $usuario->getUsername() . "'!");

            return new SelfValidatingPassport(new UserBadge($usuario->getUsername()));

        } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
            // Failed to get the access token or user details.
            throw new CustomUserMessageAuthenticationException('Erro ao efetuar o login!');
            $this->logger->error('Erro no KeyCloakAuthenticator! ' .$e->getMessage());
        }

        $this->logger->error('Erro no KeyCloakAuthenticator! Login não efetuado!');
        throw new CustomUserMessageAuthenticationException('Erro ao efetuar o login!');
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // on success, let the request continue
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            // you may want to customize or obfuscate the message first
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())

            // or to translate this message
            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }
}