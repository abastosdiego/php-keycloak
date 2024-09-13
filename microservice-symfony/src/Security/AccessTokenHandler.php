<?php

// src/Security/AccessTokenHandler.php
namespace App\Security;

use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Psr\Log\LoggerInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AccessTokenHandler implements AccessTokenHandlerInterface
{
    public function __construct(
        private LoggerInterface $logger
    ) {
    }

    public function getUserBadgeFrom(string $accessToken): UserBadge
    {
        // e.g. query the "access token" database to search for this token
        //$accessToken = $this->repository->findOneByValue($accessToken);
        //if (null === $accessToken) {
        //    throw new BadCredentialsException('Invalid credentials.');
        //}

        $publicKeyPath = __DIR__ . '/../../public_key.pem';
        $publicKey = openssl_pkey_get_public(file_get_contents($publicKeyPath));

        $decoded = JWT::decode($accessToken, new Key($publicKey, 'RS256'));
        $decoded_array = (array) $decoded;

        $this->logger->info('########## username token jwt: '. $decoded_array['preferred_username']);

        // and return a UserBadge object containing the user identifier from the found token
        // (this is the same identifier used in Security configuration; it can be an email,
        // a UUID, a username, a database ID, etc.)
        return new UserBadge($decoded_array['preferred_username']);
    }
}