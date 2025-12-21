<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: *');
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

$endpoint = 'https://unlockcontent.net/api/v2';

$aff_sub4 = $_GET['aff_sub4'] ?? '';

$data = [
    'ctype'      => '7',
    'aff_sub4'   => $aff_sub4,
    'ip'         => $_SERVER['REMOTE_ADDR'] ?? '',
    'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
];

$url = $endpoint . '?' . http_build_query($data);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer 27968|4cQStKTIiTQ4BU8CrXbYOy7Qb41JFzDPJ92dz9bsfb47f1a2'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
curl_close($ch);

$json = json_decode($response, true);
echo json_encode($json['offers'] ?? []);
