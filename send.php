<?php
// Cargar el autoload de Composer
require 'vendor/autoload.php';
/**
 * HTTP v1 API: https://medium.com/@vc21496/migrating-from-legacy-fcm-apis-to-http-v1-api-in-php-63076c5aa212
 */
use Google\Client;
function getAccessToken($serviceAccountPath)
{
  $client = new Client();
  $client->setAuthConfig($serviceAccountPath);
  $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
  $client->useApplicationDefaultCredentials();
  $token = $client->fetchAccessTokenWithAssertion();
  return $token['access_token'];
}

function sendMessage($accessToken, $projectId, $message)
{
  $url = 'https://fcm.googleapis.com/v1/projects/' . $projectId . '/messages:send';
  $headers = [
    'Authorization: Bearer ' . $accessToken,
    'Content-Type: application/json',
  ];
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['message' => $message]));

  // Desactivar la verificación SSL (solo para desarrollo)
  /* curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); */  // Desactivar la verificación del certificado SSL
  /* curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); */  // Desactivar la verificación del host SSL

  $response = curl_exec($ch);
  if ($response === false) {
    throw new Exception('Curl error: ' . curl_error($ch));
  }
  curl_close($ch);
  return json_decode($response, true);
}

// Path to your service account JSON key file
$serviceAccountPath = 'C:\Users\chris\source\git\php-fcm\pvKey.json';

// Your Firebase project ID
$projectId = 'pushnotificationssystem';

// Example message payload
$message = [
  'token' => 'eI0HVK3180Gnpid-r4qcgA:APA91bFJhjtN759Gd6EnZfrl0Mfoc3XDYhyUjZMylS1Jn_PHk-ZDZvrnqb2LzT7JwoCLypo2dDqLuKC6eVGKKq3WISgdVg1H8ZdwNZeIevjHNRdUWd3hbT91-3nfWnkbLqxrL1ZEHAEm',
  'notification' => [
    'title' => 'Título de la Notificación',
    'body' => 'Este es el cuerpo de la notificación',
    'image' => 'https://em-content.zobj.net/source/apple/391/winking-face_1f609.png'
  ],
  'webpush' => [
    'fcm_options' => [
      'link' => 'https://google.com'
    ]
  ]
];
try {
  $accessToken = getAccessToken($serviceAccountPath);
  $response = sendMessage($accessToken, $projectId, $message);
  echo 'Message sent successfully: ' . print_r($response, true);
} catch (Exception $e) {
  echo 'Error: ' . $e->getMessage();
}

/**
 * legacy FCM APIs
 */

// Usar las clases necesarias de Google y Guzzle
/* use Google\Auth\Credentials\ServiceAccountCredentials; */
/* use Google\Auth\HttpHandler\HttpHandlerFactory; */
/* use Google\Auth\HttpHandler\Guzzle6HttpHandler;
use GuzzleHttp\Client; */

// Configuración de Guzzle para desactivar SSL (opcional, si tienes problemas con SSL)
/* $guzzleClient = new Client(['verify' => false]); */


// Crear el manejador HTTP personalizado con el cliente Guzzle
/* $httpHandler = HttpHandlerFactory::build($guzzleClient); */
/* $httpHandler = new Guzzle6HttpHandler($guzzleClient); */

// Cargar las credenciales de la cuenta de servicio desde el archivo JSON
/* $credential = new ServiceAccountCredentials(
  'https://www.googleapis.com/auth/firebase.messaging',
  json_decode(file_get_contents("pvKey.json"), true)
); */

// Obtener el token de acceso utilizando las credenciales de la cuenta de servicio
/* $token = $credential->fetchAuthToken($httpHandler);
 */
// Verificar si se obtuvo correctamente el token
/* if (!isset($token['access_token'])) {
  die('Error obteniendo el token de acceso.');
} */

// Imprimir el token (opcional, para depuración)
/* echo "Token de acceso: " . $token['access_token'] . "\n"; */

// Preparar el cuerpo de la notificación
/* $notification = [
  "message" => [
    "token" => "eI0HVK3180Gnpid-r4qcgA:APA91bFJhjtN759Gd6EnZfrl0Mfoc3XDYhyUjZMylS1Jn_PHk-ZDZvrnqb2LzT7JwoCLypo2dDqLuKC6eVGKKq3WISgdVg1H8ZdwNZeIevjHNRdUWd3hbT91-3nfWnkbLqxrL1ZEHAEm",  // Aquí debes poner el token del dispositivo receptor
    "notification" => [
      "title" => "Título de la Notificación",
      "body" => "Este es el cuerpo de la notificación",
      "image" => "https://em-content.zobj.net/source/apple/391/winking-face_1f609.png"  // (Opcional) Imagen para la notificación
    ],
    "webpush" => [
      "fcm_options" => [
        "link" => "https://google.com"
      ]
    ]
  ]
]; */

// Preparar la solicitud HTTP a FCM para enviar la notificación
/* $ch = curl_init("https://fcm.googleapis.com/v1/projects/pushnotificationssystem/messages:send"); */

/* curl_setopt($ch, CURLOPT_HTTPHEADER, [
  'Content-Type: application/json',
  'Authorization: Bearer ' . $token['access_token']
]); */

/* curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($notification)); */
/* curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  */ // Obtener la respuesta
/* curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");  */ // Usar método POST

/* curl_setopt($ch, CURLOPT_POSTFIELDS, '{
  "message": {
    "token": "eI0HVK3180Gnpid-r4qcgA:APA91bFJhjtN759Gd6EnZfrl0Mfoc3XDYhyUjZMylS1Jn_PHk-ZDZvrnqb2LzT7JwoCLypo2dDqLuKC6eVGKKq3WISgdVg1H8ZdwNZeIevjHNRdUWd3hbT91-3nfWnkbLqxrL1ZEHAEm",
    "notification": {
      "title": "Background Message Title",
      "body": "Background message body",
      "image": "https://em-content.zobj.net/source/apple/391/winking-face_1f609.png"
    },
    "webpush": {
      "fcm_options": {
        "link": "https://google.com"
      }
    }
  }
}'); 

curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "post");*/

// Ejecutar la solicitud
/* $response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch); */

// Verificar la respuesta de Firebase Cloud Messaging
/* if ($error) {
  echo "cURL Error: " . $error . "\n";
} else {
  echo "HTTP Code: " . $httpCode . "\n";
  echo "Response: " . $response . "\n";
} */

// Cerrar la conexión cURL
/* curl_close($ch); */