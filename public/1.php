<?php
$client_id = "ClientCatalogueBpiOIDC";
$redirect_uri = "https://catalogue-dev.bpi.fr";
$scope = "email openid profile address phone groupes RNConfirmed pseudo";
$authorization_endpoint = "https://auth-test.bpi.fr/oauth2/authorize";

$params = http_build_query([
    'client_id' => $client_id,
    'redirect_uri' => $redirect_uri,
    'response_type' => 'code',
    'scope' => $scope,
]);

$authorization_url = "$authorization_endpoint?$params";
header("Location: $authorization_url");
exit();
?>


<!--https://auth-test.bpi.fr/oauth2/authorize?response_type=code&scope=email%20openid%20profile%20address%20phone%20groupes%20RNConfirmed%20pseudo&client_id=ClientCatalogueBpiOIDC&state=gfD524C44ahQKm&redirect_uri=https%3A%2F%2Fcatalogue-dev.bpi.fr#-->
