<?php
require 'vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$publicKey = "MIIClzCCAX8CBgGR1tNLPDANBgkqhkiG9w0BAQsFADAPMQ0wCwYDVQQDDARkYWRtMB4XDTI0MDkwOTEyNDU1N1oXDTM0MDkwOTEyNDczN1owDzENMAsGA1UEAwwEZGFkbTCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBALA3QFk0GVdWLuiAeA3BGzKn+SZwTOUEquWMb9LNfGA2mrf6ev8z9wuUB8jh2DqDT9XsoJFnyRgzwmHtMpmwI1JqBSzKZt3BTY5ikbRd8EEMjLe0XBPE5xB5Yk8a5fhrvw7ssu96Nn9RU5fCRZPQrqywCQlZJimDbPxtoPuxQuaRd0M9QoCSLyraaGNOFkbvGDd/MdRr0lXJVFnpUO1uiRNdQo3lcTb0/iiP1ELcR3J23u7eNfuBSQrG2LSUQg+STZHTmeXdR3Hyvo8eBpGVCdSN2IdiLilntBL3EA35pQyuTRzX1RaVl3XkFhIq8KJ2nzxdtM725bIgbPChT2lCynMCAwEAATANBgkqhkiG9w0BAQsFAAOCAQEAf1VIVdhak8Gf+Ibf8cK9OPKeapEphE9ntTZ5UekAamqjsW2otAIK0QyaagPbdzT+Y6j6UYZ59HOZtJ8QzPKf5YopQet0MNY2YfA3fOZfIdylrvvN13hFT3clkH4gL1vTC52+OnZ1gN6+Zrus+n3nO5W0ImA2TnHkYknz64jzuR9w6eaS31Wp3JMXIrq7EuUYSsyQvbH5MKBWQ9RLb5SLG4Ss+YY9+1ZWlv2N1Nqcyv18R2au49R/lszRrVstKXEV5f5fjNmt7yIa2mySDwMQKMP4qPLyLwYCOmV/Bpc7CS9MqFs1rLvo3Hs5Z5YmugpSsDtELi+DGZVrMNN9AHVmdw==";

session_start();
$accessToken = null;
if(isset($_SESSION['accessToken'])){
    $accessToken = $_SESSION['accessToken'];
}
$jwt = $accessToken->getToken();

$decoded = JWT::decode($jwt, new Key($publicKey, 'RS256'));
$decoded_array = (array) $decoded;

echo "Decode:\n" . print_r($decoded_array, true) . "\n";