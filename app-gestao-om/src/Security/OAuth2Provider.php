<?php
namespace App\Security;

use League\OAuth2\Client\Provider\GenericProvider;

class OAuth2Provider {
    private GenericProvider $provider;

    public function __construct() {
        $this->initializeProvider();
    }

    public function getProvider(): GenericProvider {
        return $this->provider;
    }

    private function initializeProvider(): void {
        $this->provider = new GenericProvider([
            'clientId'                => $_ENV['CLIENT_ID'],
            'clientSecret'            => $_ENV['CLIENT_SECRET'],
            'redirectUri'             => $_ENV['REDIRECT_URI'], // URL da sua aplicação
            'urlAuthorize'            => $_ENV['URL_AUTHORIZE'],
            'urlAccessToken'          => $_ENV['URL_ACCESS_TOKEN'],
            'urlResourceOwnerDetails' => $_ENV['URL_RESOURCE_OWNER_DETAILS'],
        ]);
    }
}