<?php

use Symfony\Component\Dotenv\Dotenv;

require 'vendor/autoload.php';

// Limpar sessões
session_start();
session_unset();
session_destroy();

// Redirecione o navegador para a URL de logout
(new Dotenv())->load(__DIR__.'/.env');
header("Location: ". $_ENV['URL_LOGOUT']);