<?php

require_once __DIR__ . '/bootstrap.php';

use App\Models\SupportTicket;

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$body = json_decode(file_get_contents('php://input'), true);

$required = ['full_name', 'ruc', 'email', 'category', 'subject', 'message', 'priority'];
foreach ($required as $field) {
    if (empty($body[$field])) {
        http_response_code(422);
        echo json_encode(['error' => "Field '$field' is required"]);
        exit;
    }
}

$ticket = SupportTicket::create([
    'full_name' => trim($body['full_name']),
    'ruc'       => trim($body['ruc']),
    'email'     => trim($body['email']),
    'category'  => $body['category'],
    'subject'   => trim($body['subject']),
    'message'   => trim($body['message']),
    'priority'  => $body['priority'],
]);

http_response_code(201);
echo json_encode(['success' => true, 'id' => $ticket->id]);
