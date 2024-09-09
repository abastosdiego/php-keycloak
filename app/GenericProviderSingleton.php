<?php
require 'vendor/autoload.php';

use League\OAuth2\Client\Provider\GenericProvider;
use Symfony\Component\Dotenv\Dotenv;

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

    private function loadEnv() {
        $dotenv = new Dotenv();
        $dotenv->load(__DIR__.'/.env');
    }

    private function initializeProvider(): void {
        $this->loadEnv();
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