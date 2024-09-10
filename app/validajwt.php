<?php
require 'vendor/autoload.php';

// session_start();
// $accessToken = null;
// if(isset($_SESSION['accessToken'])){
//     $accessToken = $_SESSION['accessToken'];
// }
// $jwt = $accessToken->getToken();


use Firebase\JWT\JWT;
use Firebase\JWT\Key;

#// Ler chave pública
$publicKeyPath = __DIR__ . '/public_key.pem';
$publicKey = openssl_pkey_get_public(file_get_contents($publicKeyPath));

if (!$publicKey) {
    die('Erro ao carregar a chave pública: ' . openssl_error_string());
}

$jwt = "eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJpek4zaE1pRHRCeVZFZzJIVU5WU203VjE5TGRWMHdkUmxjNDFDQzRfdUJVIn0.eyJleHAiOjE3MjU5NzU0NzQsImlhdCI6MTcyNTk3NTE3NCwiYXV0aF90aW1lIjoxNzI1OTczNTI4LCJqdGkiOiIyZmQ1Y2MyOS0wZTIxLTQ3YzItODM5MS1hNzEzMjhjYzFiZGMiLCJpc3MiOiJodHRwOi8vMTAuNi44OS44Nzo4MDgwL3JlYWxtcy9kYWRtIiwiYXVkIjoiYWNjb3VudCIsInN1YiI6ImMyNDgyNmU1LTFhNDYtNGI4ZS05ODI2LTkxZGZhMDE1NzIzZSIsInR5cCI6IkJlYXJlciIsImF6cCI6ImFwcDEiLCJzaWQiOiJiMTY3NjUyMi1iMDExLTRhZjAtOTljMy03YTdiNDk1ZGRiNDUiLCJhY3IiOiIwIiwicmVhbG1fYWNjZXNzIjp7InJvbGVzIjpbImRlZmF1bHQtcm9sZXMtZGFkbSIsIm9mZmxpbmVfYWNjZXNzIiwidW1hX2F1dGhvcml6YXRpb24iXX0sInJlc291cmNlX2FjY2VzcyI6eyJhY2NvdW50Ijp7InJvbGVzIjpbIm1hbmFnZS1hY2NvdW50IiwibWFuYWdlLWFjY291bnQtbGlua3MiLCJ2aWV3LXByb2ZpbGUiXX19LCJzY29wZSI6Im9wZW5pZCBwcm9maWxlIGVtYWlsIiwiZW1haWxfdmVyaWZpZWQiOmZhbHNlLCJuYW1lIjoiRGllZ28gQmFzdG9zIiwicHJlZmVycmVkX3VzZXJuYW1lIjoidXN1YXJpbyIsImdpdmVuX25hbWUiOiJEaWVnbyIsImZhbWlseV9uYW1lIjoiQmFzdG9zIiwiZW1haWwiOiJ1c3VhcmlvQHVzdWFyaW8uY29tIn0.WybJJOldoJwDsH2nfIOe84ZBJpOuETJmJLrBhpVIx9WLIfud-hoWLwoRZ_fZxyj7_hs2go1Eh6RDW30NAvxIL79FNi3h7Xk4xYPYo7MkyvhCbopsXqTvblC2dXo2CX3BeiE9rmP40ETwxS095qsQFN25eyzgyE7m0E3DDs6EFu2U15i9VElqV_4aWnaTglSW_KmBGPIDOTmOof9Ty0EEB-a7ssJF0GIeGO8nN0GiEkhinQSSxUreJ9rCF2HSRuYlo1p-Uu0lykdmomHLhnuUD22pu4nTmvBN0rTftM0LRannOegltwG7mI7BxJG3odz5gLhxo-RjDVkSFoEa09L4rQ";


##### Para saber qual chave pública utilizar. Procurar no Header do jwt, o "kid". Depois entrar na interface do Keycloak, entrar no healm correto, ir em "Realm settings" > "Keys". Pegar a "Public key" correspondente ao kid do jwt.

// Dividir o token JWT
list($header, $payload, $signature) = explode('.', $jwt);

// Decodificar header
$decodedHeader = base64_decode($header);
echo "Header: " . $decodedHeader . "\n";
echo "<br><br><br><br>";

########

$decoded = JWT::decode($jwt, new Key($publicKey, 'RS256'));
$decoded_array = (array) $decoded;
echo "Decode:\n" . print_r($decoded_array, true) . "\n";