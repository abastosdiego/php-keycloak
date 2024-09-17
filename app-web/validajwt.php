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

$jwt = "eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJsZmMtU2U1WDF0ZFBEOTRBWDQxbG55bElsYy01dllyOUFQOEpMTWo0blRZIn0.eyJleHAiOjE3MjYwMTc4MTksImlhdCI6MTcyNjAxNzUxOSwiYXV0aF90aW1lIjoxNzI2MDE2Njk1LCJqdGkiOiJmMjRiNzEwZS0wZjgxLTQ0MmMtYTU4Yi1hY2Y1OGRmYmE4OWIiLCJpc3MiOiJodHRwOi8vMTkyLjE2OC4xLjk6ODA4MC9yZWFsbXMvZGFkbSIsImF1ZCI6ImFjY291bnQiLCJzdWIiOiI2ZjMxYTY4OS0yM2VmLTQ3YjMtOTliMS02N2FjOTA4NzlhODIiLCJ0eXAiOiJCZWFyZXIiLCJhenAiOiJhcHAxIiwic2lkIjoiNWQ4NTYyMzItNDFjNS00ZjA2LTg3NjItZWI5NGQxMjAyYThkIiwiYWNyIjoiMCIsInJlYWxtX2FjY2VzcyI6eyJyb2xlcyI6WyJkZWZhdWx0LXJvbGVzLWRhZG0iLCJvZmZsaW5lX2FjY2VzcyIsInVtYV9hdXRob3JpemF0aW9uIl19LCJyZXNvdXJjZV9hY2Nlc3MiOnsiYWNjb3VudCI6eyJyb2xlcyI6WyJtYW5hZ2UtYWNjb3VudCIsIm1hbmFnZS1hY2NvdW50LWxpbmtzIiwidmlldy1wcm9maWxlIl19fSwic2NvcGUiOiJvcGVuaWQgcHJvZmlsZSBlbWFpbCIsImVtYWlsX3ZlcmlmaWVkIjpmYWxzZSwibmFtZSI6InVzdWFyaW8gdXN1YXJpbyIsInByZWZlcnJlZF91c2VybmFtZSI6InVzdWFyaW8iLCJnaXZlbl9uYW1lIjoidXN1YXJpbyIsImZhbWlseV9uYW1lIjoidXN1YXJpbyIsImVtYWlsIjoidXN1YXJpb0BnbWFpbC5jb20ifQ.SZM-emsMKq7Egchnyvtg6t2XmY0elK7CRtUMOruejndRiKRVB9-UZHLUJPdTcwpry_q0H9cwT7f2LPvmA9YCaChp8-H2G90MaldHrJaTJe7IdB6-Wvj8OADiCYDDt88s3Sn96jorsFtGTG_qh74Fh3kZ1MbT9KBlxkQ5oPmS730tmcCcRA65dKjVa6QN8--2pzHI2K-qzM9qzh8Qc55RPN-aHrO-banPfOAdS8T9yCgHpa2BJ29EJujUko7yaWBLxfXD4AlbZMYtjbB4p2QgEAV0PXedabnIlvKll2OfI6Y2wq7gFVtF1LuPMsVXOClIxNfAPS7TcG3wRi2-9rEJXA";

### Para saber qual chave pública utilizar. Procurar no Header do jwt, o "kid". Depois entrar na interface do Keycloak, entrar no healm correto, ir em "Realm settings" > "Keys". Pegar a "Public key" correspondente ao kid do jwt.
### Olhar também se o algoritmo ("alg") = "RS256".

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