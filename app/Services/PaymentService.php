<?php
namespace App\Services;

class PaymentService
{
    private string $serverKey;
    private string $apiUrl;
    private bool $isProd;

    public function __construct()
    {
        $cfg           = require BASE_PATH . '/config/payment.php';
        $this->serverKey = $cfg['midtrans']['server_key'] ?? '';
        $this->isProd    = $cfg['midtrans']['is_production'] ?? false;
        $this->apiUrl    = $this->isProd
            ? 'https://app.midtrans.com/snap/v1/transactions'
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';
    }

    public function createTransaction(array $data): string
    {
        if (!$this->serverKey || str_contains($this->serverKey, 'GANTI')) {
            return 'sandbox-token-dev-' . uniqid();
        }

        $payload = [
            'transaction_details' => [
                'order_id'     => $data['booking_code'],
                'gross_amount' => (int)$data['amount'],
            ],
            'customer_details' => [
                'first_name' => $data['customer']['name'],
                'email'      => $data['customer']['email'],
                'phone'      => $data['customer']['phone'],
            ],
            'callbacks' => [
                'finish'   => APP_URL . '/booking/success/' . $data['booking_id'],
                'unfinish' => APP_URL . '/booking/payment/' . $data['booking_id'],
                'error'    => APP_URL . '/booking/payment/' . $data['booking_id'],
            ],
        ];

        $ch = curl_init($this->apiUrl);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode($payload),
            CURLOPT_HTTPHEADER     => [
                'Accept: application/json',
                'Content-Type: application/json',
                'Authorization: Basic ' . base64_encode($this->serverKey . ':'),
            ],
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT        => 30,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 201) {
            error_log('[PaymentService] Midtrans error: HTTP ' . $httpCode . ' - ' . $response);
            return 'sandbox-token-fallback-' . uniqid();
        }

        $result = json_decode($response, true);
        return $result['token'] ?? ('sandbox-token-' . uniqid());
    }
}
