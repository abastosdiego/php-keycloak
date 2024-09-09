<?php

require 'vendor/autoload.php';

session_start();
session_unset();
session_destroy();

$redirectUri = 'http://localhost:8001/';
$encodedRedirectUri = urlencode($redirectUri);

// URL de logout do Keycloak
$logoutUrl = "http://10.6.89.87:8080/realms/dadm/protocol/openid-connect/logout";

// Redirecione o navegador para a URL de logout
header("Location: $logoutUrl");
exit();