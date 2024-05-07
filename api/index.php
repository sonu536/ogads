<?php
header('Access-Control-Allow-Origin: *');
$xffaddrs = explode(',',$_SERVER['HTTP_X_FORWARDED_FOR']);
$_SERVER['REMOTE_ADDR'] = $xffaddrs[0];
$endpoint = 'https://unlockcontent.net/api/v2';
$data = [
    'ip' => $_SERVER['REMOTE_ADDR'], // Client IP (REQUIRED)
    'user_agent' => $_SERVER['HTTP_USER_AGENT'], // Client User Agent (REQUIRED)
    // Enter other optional vars here (ctype, max, etc)
];
$url = $endpoint . '?' . http_build_query($data);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url,);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Authorization: Bearer 25416|3BbSuzjid8I9iXuhJnXWfWChhbm1m2bkiMEQfrBg3411d3ec'
));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
curl_close($ch);
$result = json_decode($result);
$offers = $result->offers;
header("Content-type: application/json; charset=utf-8");
echo json_encode($offers);
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script type="text/javascript">
var queryString = new URL(window.location).search;
document.querySelectorAll("[href]").forEach(link => {
    var current = link.href;
    const queryStrToUse = queryString.replace('?', '&')
    link.href = current + queryStrToUse;
});
</script>
?>
