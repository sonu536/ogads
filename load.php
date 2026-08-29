<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

$aff_sub4 = filter_input(INPUT_GET, 'aff_sub4', FILTER_DEFAULT) ?? '';

// Grab the real visitor IP
$client_ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip_list = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
    $first_ip = trim($ip_list[0]);
    if (filter_var($first_ip, FILTER_VALIDATE_IP)) {
        $client_ip = $first_ip;
    }
}

$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Mozilla/5.0';

// OgAds API call
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

// 1. Detect visitor country from API metadata (OgAds usually returns `country_code` at root)
$user_country = strtoupper(trim($data['country_code'] ?? $data['country'] ?? ''));

// 2. Define Tiers
$tiers = [
    '0.70' => ['US', 'GB', 'CA', 'AU', 'NZ', 'DE', 'FR', 'NL', 'CH', 'NO', 'SE', 'DK'],
    '0.40' => ['ES', 'IT', 'IE', 'JP', 'KR', 'SG', 'HK', 'AE', 'SA', 'PL', 'CZ', 'MY', 'TH', 'TW'],
    '0.12' => ['PH', 'ID', 'VN', 'NG', 'KE', 'GH', 'EG', 'BR', 'MX', 'TR'],
];

// 3. Helper to determine the minimum payout threshold for any given country
$getMinPayout = function($countryCode) use ($tiers) {
    if (empty($countryCode)) return 0.07;
    
    foreach ($tiers as $min => $countries) {
        if (in_array(strtoupper($countryCode), $countries, true)) {
            return (float)$min;
        }
    }
    return 0.07;
};

// 4. Determine baseline minimum for current visitor
$visitor_min_payout = $getMinPayout($user_country);

// 5. Filter offers
$filtered_offers = [];
foreach ($raw_offers as $offer) {
    // Clean payout value (strip any currency signs if present)
    $clean_payout_str = preg_replace('/[^0-9.]/', '', (string)($offer['payout'] ?? '0'));
    $payout = (float)$clean_payout_str;

    // Resolve offer countries (handles array, comma-separated string, or single string)
    $offer_countries = [];
    if (isset($offer['country_code'])) {
        $offer_countries = is_array($offer['country_code']) ? $offer['country_code'] : explode(',', $offer['country_code']);
    } elseif (isset($offer['country'])) {
        $offer_countries = is_array($offer['country']) ? $offer['country'] : explode(',', $offer['country']);
    }

    $offer_countries = array_filter(array_map('trim', array_map('strtoupper', $offer_countries)));

    // Calculate effective minimum required
    if (!empty($user_country)) {
        // If we know the user's geo, use the visitor's tier
        $min_required = $visitor_min_payout;
    } elseif (!empty($offer_countries)) {
        // If checking based on offer targets, find the highest tier matching any targeted country
        $min_required = 0.07;
        foreach ($offer_countries as $c) {
            $c_min = $getMinPayout($c);
            if ($c_min > $min_required) {
                $min_required = $c_min;
            }
        }
    } else {
        $min_required = 0.07;
    }

    // Strict payout check (using round to prevent floating point inaccuracy e.g. 0.699999999)
    if (round($payout, 2) >= round($min_required, 2)) {
        $filtered_offers[] = $offer;
    }

    if (count($filtered_offers) === 4) {
        break;
    }
}

echo json_encode($filtered_offers);
