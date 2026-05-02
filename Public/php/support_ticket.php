<?php

ini_set('display_errors', 0);
error_reporting(0);

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

require_once __DIR__ . '/SupabaseClient.php';

$body = json_decode(file_get_contents('php://input'), true);

if (!$body) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON body']);
    exit;
}

$required = ['full_name', 'ruc', 'email', 'category', 'subject', 'message', 'priority'];
foreach ($required as $field) {
    if (empty($body[$field])) {
        http_response_code(422);
        echo json_encode(['error' => "Field '$field' is required"]);
        exit;
    }
}

try {
    $supabase = new SupabaseClient(
        getenv('SUPABASE_URL'),
        getenv('SUPABASE_ANON_KEY')
    );

    $ticket = $supabase->insert('support_tickets', [
        'full_name' => trim($body['full_name']),
        'ruc'       => trim($body['ruc']),
        'email'     => trim($body['email']),
        'category'  => $body['category'],
        'subject'   => trim($body['subject']),
        'message'   => trim($body['message']),
        'priority'  => $body['priority'],
    ]);

    http_response_code(201);
    echo json_encode(['success' => true, 'id' => $ticket['id'] ?? null]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
