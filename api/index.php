<?php

// ---- CORS (MUST be first, before any output)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json; charset=utf-8');

// ---- Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// ---- Safe IP handling
if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $xffaddrs = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
    $ip = trim($xffaddrs[0]);
} else {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
}

// ---- Safe aff_sub4
$aff_sub4 = $_GET['aff_sub4'] ?? '';

// ---- Build API request
$endpoint = 'https://unlockcontent.net/api/v2';

$data = [
    'ctype'      => '7',
    'aff_sub4'   => $aff_sub4,
    'ip'         => $ip,
    'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
];

$url = $endpoint . '?' . http_build_query($data);

// ---- cURL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer 27968|4cQStKTIiTQ4BU8CrXbYOy7Qb41JFzDPJ92dz9bsfb47f1a2'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$result = curl_exec($ch);
curl_close($ch);

$json = json_decode($result, true);

// ---- Safe output
echo json_encode($json['offers'] ?? []);
