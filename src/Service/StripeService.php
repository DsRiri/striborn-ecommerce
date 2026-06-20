<?php

namespace App\Service;

use Stripe\Stripe;
use Stripe\Checkout\Session;

class StripeService
{
    private string $secretKey;
    private string $publishableKey;

    public function __construct(string $secretKey, string $publishableKey)
    {
        $this->secretKey = $secretKey;
        $this->publishableKey = $publishableKey;
        Stripe::setApiKey($this->secretKey);
    }

    public function getPublishableKey(): string
    {
        return $this->publishableKey;
    }

    public function createCheckoutSession(array $cart, string $successUrl, string $cancelUrl): Session
    {
        $lineItems = [];
        foreach ($cart as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $item['name'] . ' (Taille: ' . $item['size'] . ')',
                    ],
                    'unit_amount' => (int)($item['price'] * 100),
                ],
                'quantity' => 1,
            ];
        }

        return Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
        ]);
    }
}