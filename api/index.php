<?php
// 1. Headers (Must be at the very top)
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');

error_reporting(0);

// 2. Identify the Real User IP
$user_ip = $_SERVER['REMOTE_ADDR'];
if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $addr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
    $user_ip = trim($addr[0]);
}

// 3. Prepare Data
$aff_sub4 = isset($_GET["aff_sub4"]) ? htmlspecialchars($_GET["aff_sub4"]) : '';
$endpoint = 'https://unlockcontent.net/api/v2';

$params = [
    'ctype'      => '7',
    'aff_sub4'   => $aff_sub4,
    'ip'         => $user_ip,
    'user_agent' => $_SERVER['HTTP_USER_AGENT']
];

// 4. Execute cURL
$ch = curl_init($endpoint . '?' . http_build_query($params));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer 27968|4cQStKTIiTQ4BU8CrXbYOy7Qb41JFzDPJ92dz9bsfb47f1a2'
]);

$response = curl_exec($ch);
curl_close($ch);

// 5. Decode and Output
$data = json_decode($response, true);
$offers = isset($data['offers']) ? $data['offers'] : [];

echo json_encode($offers);
