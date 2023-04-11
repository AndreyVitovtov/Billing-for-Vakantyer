<?php

namespace Model;

class Payriff extends Log
{
    private $mainUrl = PAYRIFF_MAIN_URL;
    private $approveURL = PAYRIFF_APPROVE_URL;
    private $cancelURL = PAYRIFF_CANCEL_URL;
    private $declineURL = PAYRIFF_DECLINE_URL;
    private $currencyType = PAYRIFF_CURRENCY_TYPE;
    private $merchant = PAYRIFF_MERCHANT;
    private $language = PAYRIFF_LANGUAGE;

    public function __construct()
    {
        parent::__construct(get_class());
    }

    public function createOrder($amount, $description)
    {
        return $this->makeRequest('createOrder', [
            'body' => [
                'amount' => $amount,
                'currencyType' => $this->currencyType,
                'description' => $description,
                'language' => $this->language,
                'approveURL' => $this->approveURL,
                'cancelURL' => $this->cancelURL,
                'declineURL' => $this->declineURL,
            ],
            'merchant' => $this->merchant
        ]);
    }

    public function getStatusOrder($orderId, $sessionId)
    {
        return $this->makeRequest('getStatusOrder', [
            'body' => [
                'language' => $this->language,
                'orderId' => $orderId,
                'sessionId' => $sessionId
            ],
            'merchant' => $this->merchant
        ]);
    }

    private function makeRequest($method, $params, $associative = true)
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $this->mainUrl . $method,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($params),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: ' . PAYRIFF_SECRET_KEY
            ]
        ]);
        $request = curl_exec($ch);
        $this->setLog($method,
            "POST:\n" . json_encode($params) .
            "\n\nREQUEST:\n" . $request .
            "\n\nHTTP CODE:\n " . curl_getinfo($ch, CURLINFO_HTTP_CODE)
        );
        curl_close($ch);
        if ($associative) return json_decode($request, true);
        return $request;
    }
}