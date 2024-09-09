<?php
require 'vendor/autoload.php';
require 'GenericProviderSingleton.php';

$provider = GenericProviderSingleton::getInstance()->getProvider();

// If we don't have an authorization code then get one
if (!isset($_GET['code'])) {

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

        // We have an access token, which we may use in authenticated
        // requests against the service provider's API.
        echo 'Access Token: ' . $accessToken->getToken() . "<br>";
        echo 'Refresh Token: ' . $accessToken->getRefreshToken() . "<br>";
        echo 'Expired in: ' . $accessToken->getExpires() . "<br>";
        echo 'Already expired? ' . ($accessToken->hasExpired() ? 'expired' : 'not expired') . "<br>";

        // // Using the access token, we may look up details about the
        // // resource owner.
        $resourceOwner = $provider->getResourceOwner($accessToken);
        $arrayUsuarioLogado = $resourceOwner->toArray();

        echo  'name: ' . $arrayUsuarioLogado['name'] . '<br>';
        echo  'email: ' . $arrayUsuarioLogado['email'] . '<br>';

        session_start();
        $_SESSION['user_name'] = $arrayUsuarioLogado['name'];
        $_SESSION['user_email'] = $arrayUsuarioLogado['email'];
        $_SESSION['user_token'] = $accessToken->getToken();
        $_SESSION['user_refresh_token'] = $accessToken->getRefreshToken();

        // Redirect the user to the authorization URL.
        header('Location: logado.php');
        exit;

    } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {

        // Failed to get the access token or user details.
        exit($e->getMessage());

    }

}