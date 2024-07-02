<?php
$access_token = $_GET['access_token'];  // Le code récupéré après l'authentification

$userinfo_endpoint = "https://auth-test.bpi.fr/oauth2/userinfo";
$options = [
    'http' => [
        'header' => "Authorization: Bearer $access_token\r\n",
        'method' => 'GET',
    ],
];

$context  = stream_context_create($options);
$response = file_get_contents($userinfo_endpoint, false, $context);
$user_info = json_decode($response, true);

print_r($user_info);  // Affiche les informations de l'utilisateur comme le nom, l'email, etc.
?>
