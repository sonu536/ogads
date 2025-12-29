<?php
// Define variables FIRST - no warnings
$aff_sub4 = isset($_GET["aff_sub4"]) ? htmlspecialchars($_GET["aff_sub4"]) : '';
error_reporting(0); // Suppress warnings completely

// Headers FIRST
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header("Content-type: application/json; charset=utf-8");

$xffaddrs = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
$_SERVER['REMOTE_ADDR'] = $xffaddrs[0];

$endpoint = 'https://lockedapp.org/api/v2';
$ctype = '5';
$data = [
  'ctype' => $ctype,
  'aff_sub4' => $aff_sub4,
  'ip' => $_SERVER['REMOTE_ADDR'],
  'user_agent' => $_SERVER['HTTP_USER_AGENT'] // PASS REAL UA - gets correct mobile/desktop offers
];

$url = $endpoint . '?' . http_build_query($data);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // Real client UA
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  'Authorization: Bearer 27968|4cQStKTIiTQ4BU8CrXbYOy7Qb41JFzDPJ92dz9bsfb47f1a2'
));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
curl_close($ch);

$result = json_decode($result);
$offers = $result->offers ?? [];
echo json_encode($offers);
?>
