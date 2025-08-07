<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = file_get_contents("php://input");

    if (!$data) {
        http_response_code(400);
        echo json_encode(["error" => "No data received"]);
        exit;
    }

    $decoded = json_decode($data, true);
    if ($decoded === null) {
        http_response_code(400);
        echo json_encode(["error" => "Invalid JSON"]);
        exit;
    }

    file_put_contents('schedule.json', json_encode($decoded, JSON_PRETTY_PRINT));
    echo json_encode(["status" => "success"]);
}
