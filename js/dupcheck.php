<?php
header('Content-Type: application/json');

// Get the request data from the client-side
$requestBody = file_get_contents('php://input');
$requestData = json_decode($requestBody, true);
$content = $requestData['content'];

// Forward the request to the API
$apiKey = '5093384-ty543rf-457908sdjf';
$apiUrl = 'https://api.seoreviewtools.com/plagiarism/?content=1&querylimit=50&key=' . $apiKey;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('content' => $content)));
$response = curl_exec($ch);
curl_close($ch);

// Return the API response to the client-side
echo $response;
