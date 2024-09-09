<?php
require 'vendor/autoload.php';

use League\OAuth2\Client\Provider\GenericProvider;

class GenericProviderSingleton {
    private static $instance = null;
    private GenericProvider $provider;

    private function __construct() {
        $this->initializeProvider();
    }

    // Impede a clonagem do objeto
    private function __clone() {}

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getProvider(): GenericProvider {
        return $this->provider;
    }

    private function initializeProvider(): void {
        $this->provider = new GenericProvider([
            'clientId'                => 'app1',
            'clientSecret'            => 'erUErJCtpc4wpfaUoVnflHDXNx63uZG3',
            'redirectUri'             => 'http://10.6.89.87:8001/',  // URL da sua aplicação
            'urlAuthorize'            => 'http://10.6.89.87:8080/realms/dadm/protocol/openid-connect/auth',
            'urlAccessToken'          => 'http://10.6.89.87:8080/realms/dadm/protocol/openid-connect/token',
            'urlResourceOwnerDetails' => 'http://10.6.89.87:8080/realms/dadm/protocol/openid-connect/userinfo',
        ]);
    }
}
