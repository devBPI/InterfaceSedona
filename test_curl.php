<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://www.google.com");



$proxy = 'https://spxy.bpi.fr:3128'; // Adresse et port du proxy
curl_setopt($ch, CURLOPT_PROXY, $proxy);
curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP); // Type de proxy HTTP

// Option pour ignorer les erreurs de certificat SSL (utilisé pour le débogage, pas recommandé en production)
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 2);

curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2); // Essayez différentes versions si nécessaire

// Option pour récupérer le résultat au lieu de l'afficher directement
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
if(curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
} else {
    echo 'Success:' . $response;
}
curl_close($ch);
?>
