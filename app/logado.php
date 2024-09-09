<?php
require 'vendor/autoload.php';

session_start();

if (!$_SESSION['user_name']) {
    // Redirect the user to the authorization URL.
    header('Location: /');
    exit;
} else {
    echo 'Bem-vindo ' . $_SESSION['user_name'] . '!<br>';
    echo 'Área logada do sistema';
    //$_SESSION['user_name'];
    //$_SESSION['user_email'];
    //$_SESSION['user_token'];
    //$_SESSION['user_refresh_token'];
}
        