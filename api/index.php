<?php
header('Access-Control-Allow-Origin: *');
$xffaddrs = explode(',',$_SERVER['HTTP_X_FORWARDED_FOR']);
$_SERVER['REMOTE_ADDR'] = $xffaddrs[0];
$endpoint = 'https://unlockcontent.net/api/v2';
    if(isset($_GET["aff_sub4"])) {
     $aff_sub4 = htmlspecialchars($_GET["aff_sub4"]);
};
$ctype= '7';
$data = [
    'ctype' => $ctype,
    'aff_sub4' => $aff_sub4,
    'ip' => $_SERVER['REMOTE_ADDR'], // Client IP (REQUIRED)
    'user_agent' => $_SERVER['HTTP_USER_AGENT'], // Client User Agent (REQUIRED)
    // Enter other optional vars here (ctype, max, etc)
];
$url = $endpoint . '?' . http_build_query($data);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url,);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Authorization: Bearer 27968|4cQStKTIiTQ4BU8CrXbYOy7Qb41JFzDPJ92dz9bsfb47f1a2'
));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
curl_close($ch);
$result = json_decode($result);
$offers = $result->offers;
header("Content-type: application/json; charset=utf-8");
echo json_encode($offers);
?>
