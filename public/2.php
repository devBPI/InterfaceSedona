<?php
$client_id = "ClientCatalogueBpiOIDC";
$redirect_uri = "https://catalogue-dev.bpi.fr";
$scope = "email openid profile address phone groupes RNConfirmed pseudo";
$authorization_endpoint = "https://auth-test.bpi.fr/oauth2/authorize";

$token_endpoint = "https://auth-test.bpi.fr/oauth2/token";
$code = $_GET['code'];  // Le code récupéré après l'authentification

$data = [
    'grant_type' => 'authorization_code',
    'code' => $code,
    'redirect_uri' => $redirect_uri,
    'client_id' => $client_id,
    'client_secret' => 'gfD524C44ahQKm',
];
echo "<span>data : </span>";
print_r($data);
echo "<br />";

$options = [
    'http' => [
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data),
    ],
];
echo "<span>options : </span>";
print_r($options);
echo "<br />";

$context  = stream_context_create($options);
echo "<span>context : </span>";
print_r($context);
echo "<br />";
$response = file_get_contents($token_endpoint, false, $context);
echo "<span>response : </span>";
print_r($response);
echo "<br />";




/*$ch = curl_init($token_endpoint);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/x-www-form-urlencoded'
]);

$response = curl_exec($ch);
if (curl_errno($ch)) {
    // Handle error
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);

echo "<span>response : </span>";
print_r($response);
echo "<br />";
*/




$token_response = json_decode($response, true);
echo "<span>token_response : </span>";
print_r($token_response);
echo "<br />";
$access_token = $token_response['access_token'];
echo "<span>access_token : </span>";
print_r($access_token);
echo "<br />";
?>
