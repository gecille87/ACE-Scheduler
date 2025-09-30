<?php
// api/fruitask.php

header('Content-Type: application/json');

// Your secure token and API key
$token = 'YOUR FT Token';
$apiKey = 'YOUR API KEY';

$fruitaskUrl = "https://api.fruitask.com/v3/tables/$token/rows/?api_key=$apiKey";

// Determine request method (GET or POST)
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // Fetch bookings from Fruitask
    $response = file_get_contents($fruitaskUrl);
    http_response_code(200);
    echo $response;
} elseif ($method === 'POST') {
    // Add booking to Fruitask
    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['Name'], $input['Info'], $input['Status'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }

    $options = [
        'http' => [
            'header'  => "Content-type: application/json\r\n",
            'method'  => 'POST',
            'content' => json_encode($input),
        ],
    ];

    $context = stream_context_create($options);
    $result = file_get_contents($fruitaskUrl, false, $context);

    if ($result === false) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to add booking']);
    } else {
        http_response_code(200);
        echo $result;
    }
}
