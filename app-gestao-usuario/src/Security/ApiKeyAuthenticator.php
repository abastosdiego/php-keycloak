<?php

// src/Security/ApiKeyAuthenticator.php
namespace App\Security;

use App\Entity\Usuario;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
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
use Throwable;

class ApiKeyAuthenticator extends AbstractAuthenticator
{
    public function __construct(private UsuarioRepository $usuarioRepository, private EntityManagerInterface $entityManager) {
    }

    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning `false` will cause this authenticator
     * to be skipped.
     */
    public function supports(Request $request): ?bool
    {
        return $request->headers->has('Authorization');
    }

    public function authenticate(Request $request): Passport
    {
        $authorization = $request->headers->get('Authorization');
        if (null === $authorization) {
            throw new CustomUserMessageAuthenticationException('No API token provided');
        }

        // Extrai o token JWT do cabeçalho Authorization
        $jwt = str_replace('Bearer ', '', $authorization); // Remove "Bearer " do início

        #// Lê a chave pública
        $publicKeyPath = __DIR__ . '/public_key.pem';
        $publicKey = openssl_pkey_get_public(file_get_contents($publicKeyPath));

        if (!$publicKey) {
            throw new CustomUserMessageAuthenticationException('Erro ao carregar a chave pública! ' . openssl_error_string());
        }

        try {
            $decoded = JWT::decode($jwt, new Key($publicKey, 'RS256'));
            $arrayUsuario = (array) $decoded;
        } catch (Throwable $ex) {
            throw new CustomUserMessageAuthenticationException('JWT inválido! ' . $ex->getMessage());
        }
        
        $usuario = $this->usuarioRepository->findOneBy(['uuid' => $arrayUsuario['sub']]);

        //Cadastra o usuário caso não exista
        if(!$usuario){
            $usuario = new Usuario();
            $usuario->setUuid($arrayUsuario['sub']);
            $usuario->setNome($arrayUsuario['name']);
            $usuario->setRoles(array('ROLE_USER'));

            // tell Doctrine you want to (eventually) save the Product (no queries yet)
            $this->entityManager->persist($usuario);

            // actually executes the queries (i.e. the INSERT query)
            $this->entityManager->flush();
        }

        return new SelfValidatingPassport(new UserBadge($usuario->getUuid()));
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