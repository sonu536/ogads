<?php
$aff_sub4 = filter_input(INPUT_GET, 'aff_sub4', FILTER_DEFAULT) ?? '';

$client_ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip_list = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
    $first_ip = trim($ip_list[0]);
    if (filter_var($first_ip, FILTER_VALIDATE_IP)) {
        $client_ip = $first_ip;
    }
}

$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Mozilla/5.0';

$endpoint = 'https://lockedapp.org/api/v2';
$api_token = '27968|4cQStKTIiTQ4BU8CrXbYOy7Qb41JFzDPJ92dz9bsfb47f1a2';

$url = $endpoint . '?' . http_build_query([
    'aff_sub4'   => $aff_sub4,
    'ip'         => $client_ip,
    'user_agent' => $user_agent,
]);

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL            => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_USERAGENT      => $user_agent,
    CURLOPT_TIMEOUT        => 8,
    CURLOPT_HTTPHEADER     => [
        'Authorization: Bearer ' . $api_token,
        'Accept: application/json'
    ],
]);

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);
$raw_offers = $data['offers'] ?? [];

$tiers = [
    0.70 => ['US', 'GB', 'CA', 'AU', 'NZ', 'DE', 'FR', 'NL', 'CH', 'NO', 'SE', 'DK'],
    0.40 => ['ES', 'IT', 'IE', 'JP', 'KR', 'SG', 'HK', 'AE', 'SA', 'PL', 'CZ', 'MY', 'TH', 'TW'],
    0.12 => ['PH', 'ID', 'VN', 'NG', 'KE', 'GH', 'EG', 'BR', 'MX', 'TR'],
];

$getTierMin = function($country) use ($tiers) {
    foreach ($tiers as $min => $countries) {
        if (in_array(strtoupper($country), $countries, true)) {
            return (float)$min;
        }
    }
    return 0.07;
};

$filtered_offers = [];
foreach ($raw_offers as $offer) {
    $payout = (float)($offer['payout'] ?? 0);
    $country = $offer['country'] ?? '';
    
    if ($payout >= $getTierMin($country)) {
        $filtered_offers[] = $offer;
    }
    
    if (count($filtered_offers) === 4) {
        break;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Offers</title>
</head>
<body>

<div id="offerContainer">
    <?php foreach ($filtered_offers as $o): ?>
        <center>
            <div id="offer">
                <a class="offer" href="<?= htmlspecialchars($o['link']) ?>" target="_blank" rel="noopener noreferrer">
                    <?= htmlspecialchars($o['name_short']) ?>
                    <p><?= htmlspecialchars($o['adcopy']) ?></p>
                </a>
            </div>
        </center>
    <?php endforeach; ?>
</div>

</body>
</html>
