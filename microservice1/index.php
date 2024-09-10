<?php

// Define o cabeçalho para JSON
header('Content-Type: application/json');

// Define um array com 10 produtos de exemplo
$retorno = array("mensagem" => "Microservice de produtos");

// Converte o array para JSON e exibe
echo json_encode($retorno);