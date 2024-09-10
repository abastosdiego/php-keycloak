<?php
require '../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Ler chave pública
$publicKeyPath = __DIR__ . '/public_key.pem';
$publicKey = openssl_pkey_get_public(file_get_contents($publicKeyPath));

// Ler chave privada
$privateKeyPath = __DIR__ . '/private_key.pem';
$privateKey = openssl_pkey_get_private(file_get_contents($privateKeyPath));

if (!$privateKey) {
    die('Erro ao carregar a chave privada: ' . openssl_error_string());
}

$payload = [
    'uuid' => 'a009702c-2835-46af-b6b1-4edaff37e2ad',
    'email' => 'd.bastos@gmail.com',
    'nome' => 'Diego Bastos'
];

$jwt = JWT::encode($payload, $privateKey, 'RS256');
echo "Encode:\n" . print_r($jwt, true) . "\n";

echo "<br><br><br><br>";

$decoded = JWT::decode($jwt, new Key($publicKey, 'RS256'));
$decoded_array = (array) $decoded;
echo "Decode:\n" . print_r($decoded_array, true) . "\n";