<?php
require 'vendor/autoload.php';
require 'GenericProviderSingleton.php';

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

        session_start();
        $_SESSION['user_uuid'] = $arrayUsuarioLogado['sub'];
        $_SESSION['user_full_name'] = $arrayUsuarioLogado['name'];
        $_SESSION['user_username'] = $arrayUsuarioLogado['preferred_username'];
        $_SESSION['user_email'] = $arrayUsuarioLogado['email'];
        $_SESSION['accessToken'] = $accessToken;

        // Redirect the user to the authorization URL.
        header('Location: logado.php');
        exit;

    } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
        // Failed to get the access token or user details.
        exit($e->getMessage());
    }

}