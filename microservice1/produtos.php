<?php
require 'vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Obtém o cabeçalho Authorization
$headers = getallheaders();

if (!isset($headers['Authorization'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Authorization header not found']);
    exit;
}

// Extrai o token JWT do cabeçalho Authorization
$authHeader = $headers['Authorization'];
$jwt = str_replace('Bearer ', '', $authHeader); // Remove "Bearer " do início

#// Ler chave pública
$publicKeyPath = __DIR__ . '/public_key.pem';
$publicKey = openssl_pkey_get_public(file_get_contents($publicKeyPath));

if (!$publicKey) {
    die('Erro ao carregar a chave pública: ' . openssl_error_string());
}

// Define o cabeçalho para JSON
header('Content-Type: application/json');

try {
    $decoded = JWT::decode($jwt, new Key($publicKey, 'RS256'));
    //$decoded_array = (array) $decoded;
    //echo $decoded_array['name'] . "<br>";
    //echo $decoded_array['email'] . "<br>";
    //echo json_encode($decoded_array, true);
    //exit;
} catch (Throwable $ex) {
    http_response_code(500);
    echo json_encode(['error' => 'JWT inválido!']);
    exit;
}

// Define um array com 10 produtos de exemplo
$produtos = [
    ["id" => 1, "nome" => "Smartphone Samsung Galaxy S21", "preco" => 699.99],
    ["id" => 2, "nome" => "Laptop Dell XPS 13", "preco" => 999.99],
    ["id" => 3, "nome" => "Fone de Ouvido Bose QuietComfort 35", "preco" => 299.99],
    ["id" => 4, "nome" => "Câmera Canon EOS Rebel T7", "preco" => 449.99],
    ["id" => 5, "nome" => "Smartwatch Apple Watch Series 8", "preco" => 399.99],
    ["id" => 6, "nome" => "Tablet Apple iPad Air", "preco" => 599.99],
    ["id" => 7, "nome" => "TV Sony Bravia 55\" 4K", "preco" => 1199.99],
    ["id" => 8, "nome" => "Console de Videogame PlayStation 5", "preco" => 499.99],
    ["id" => 9, "nome" => "Impressora HP OfficeJet Pro", "preco" => 199.99],
    ["id" => 10, "nome" => "Monitor LG UltraWide 34\"", "preco" => 349.99],
];

// Converte o array para JSON e exibe
echo json_encode($produtos);