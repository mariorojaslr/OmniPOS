<?php
$bunnyKey = '8b078c5f-ad56-4ad8-a4a7b28e775f-63eb-4d16';

$bunnyZone = 'gente-piola';
$bunnyHost = 'ny.storage.bunnycdn.com';

$path = 'testdir';
$filename = 'testimg.txt';

$bunnyApiUrl = "https://{$bunnyHost}/{$bunnyZone}/$path/$filename";

$ch = curl_init($bunnyApiUrl);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
curl_setopt($ch, CURLOPT_POSTFIELDS, "This is a test image content");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "AccessKey: $bunnyKey",
    "Content-Type: application/octet-stream"
]);

$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpcode\n";
echo "Response: $response\n";
