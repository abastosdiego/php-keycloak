<?php
require 'vendor/autoload.php';

session_start();
$accessToken = null;
if (isset($_SESSION['accessToken'])) {
    $accessToken = $_SESSION['accessToken'];
}

if (!$accessToken || $accessToken->hasExpired()) {
    // Redirect the user to the authorization URL.
    header('Location: /');
    exit;
}

// URL do microserviço
$apiUrl = 'http://microservice1:8000/produtos.php';

// Inicializa a sessão cURL
$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Define o cabeçalho Authorization com o token JWT
$jwtToken = $accessToken->getToken();

$headers = [
    "Authorization: Bearer $jwtToken",
    "Content-Type: application/json"
];

curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

// Faz a requisição GET para o microserviço
$json = curl_exec($ch);

curl_close($ch);

$produtos = json_decode($json, true);

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplicação 1</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
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
            padding: 3px 0;
            position: absolute;
            bottom: 0;
            width: 100%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #333;
            color: white;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>Aplicação 1</h1>
            <nav>
                <ul>
                    <li><a href="/">Início</a></li>
                    <li><a href="/produtos.php">Produtos</a></li>
                    <li><a href="/logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <section>
            <h2>Lista de produtos</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Preço</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($produtos && is_array($produtos)): ?>
                        <?php foreach ($produtos as $produto): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($produto['id']); ?></td>
                                <td><?php echo htmlspecialchars($produto['nome']); ?></td>
                                <td><?php echo number_format($produto['preco'], 2, ',', '.'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3">Nenhum produto encontrado.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </div>
    
</body>
</html>
