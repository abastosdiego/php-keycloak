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
}

// echo 'Bem-vindo ' . $_SESSION['user_full_name'] . '!<br>';
// echo 'Área logada do sistema.<br>';
// echo 'Access Token: ' . $accessToken->getToken() . "<br>";
// echo 'Refresh Token: ' . $accessToken->getRefreshToken() . "<br>";
// echo 'Expired in: ' . $accessToken->getExpires() . "<br>";
// echo 'Already expired? ' . ($accessToken->hasExpired() ? 'expired' : 'not expired') . "<br>";
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplicação 2</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        header {
            background: #333;
            color: #fff;
            padding: 2px 0;
            text-align: center;
        }
        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        nav ul li {
            display: inline;
            margin: 0 10px;
        }
        nav ul li a {
            color: #fff;
            text-decoration: none;
            padding: 5px 10px;
            display: inline-block;
        }
        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
        }
        footer {
            background: #333;
            color: #fff;
            text-align: center;
            padding: 10px 0;
            position: absolute;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>Aplicação 2</h1>
            <nav>
                <ul>
                    <li><a href="/logado.php">Início</a></li>
                    <li><a href="/logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <section>
            <h2>Bem-vindo <?php echo $_SESSION['user_full_name'] ?> !</h2>
            <p>Essa é a área logada do sistema.</p>
        </section>
    </div>

    <footer>
        <p>&copy; 2024 Minha Página de Exemplo</p>
    </footer>
</body>
</html>
