<?php
return [
    'midtrans' => [
        'client_key'    => $_ENV['MIDTRANS_CLIENT_KEY']    ?? '',
        'server_key'    => $_ENV['MIDTRANS_SERVER_KEY']    ?? '',
        'is_production' => ($_ENV['MIDTRANS_IS_PRODUCTION'] ?? 'false') === 'true',
        'snap_url_prod' => 'https://app.midtrans.com/snap/snap.js',
        'snap_url_sb'   => 'https://app.sandbox.midtrans.com/snap/snap.js',
    ],
    'google_maps' => [
        'key' => $_ENV['GOOGLE_MAPS_KEY'] ?? '',
    ],
];
