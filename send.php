<?php
use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Auth\HttpHandler\HttpHandlerFactory;
/* https://fcm.googleapis.com/v1/projects/<YOUR-PROJECT-ID>/messages:send
Content-Type: application/json
Authorization: bearer <YOUR-ACCESS-TOKEN>

{
  "message": {
    "token": "eEz-Q2sG8nQ:APA91bHJQRT0JJ...",
    "notification": {
      "title": "Background Message Title",
      "body": "Background message body"
    },
    "webpush": {
      "fcm_options": {
        "link": "https://dummypage.com"
      }
    }
  }
} */

require 'vendor/autoload.php';

$credential = new ServiceAccountCredentials(
  'https://www.googleapis.com/auth/firebase.messaging',
  json_decode(file_get_contents("pvKey.json"), true)
);

$token = $credential->fetchAuthToken(HttpHandlerFactory::build());

$ch = curl_init("https://fcm.googleapis.com/v1/projects/pushnotificationssystem/messages:send");
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  'Content-Type: application/json',
  'Authorization: Bearer ' . $token['access_token']
]);

curl_setopt($ch, CURLOPT_POSTFIELDS, '{
  "message": {
    "token": "<PK>",
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

curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "post");
$response = curl_exec($ch);
curl_close($ch);
echo $response;