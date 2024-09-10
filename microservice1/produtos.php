<?php

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

// Define o cabeçalho para JSON
header('Content-Type: application/json');

// Converte o array para JSON e exibe
echo json_encode($produtos);