<?php
require 'vendor/autoload.php';

session_start();
$accessToken = null;
if(isset($_SESSION['accessToken'])){
    $accessToken = $_SESSION['accessToken'];
}

if (!$accessToken || $accessToken->hasExpired()) {
    // Redirect the user to the authorization URL.
    header('Location: /');
    exit;
} else {
    echo 'Bem-vindo ' . $_SESSION['user_full_name'] . '!<br>';
    echo 'Área logada do sistema.<br>';
    echo 'Access Token: ' . $accessToken->getToken() . "<br>";
    echo 'Refresh Token: ' . $accessToken->getRefreshToken() . "<br>";
    echo 'Expired in: ' . $accessToken->getExpires() . "<br>";
    echo 'Already expired? ' . ($accessToken->hasExpired() ? 'expired' : 'not expired') . "<br>";
}
        