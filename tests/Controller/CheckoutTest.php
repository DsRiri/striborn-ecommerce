<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CheckoutTest extends WebTestCase
{
    public function testCheckoutWithStripe(): void
    {
        $client = static::createClient();
        
        // Ajouter un produit au panier
        $client->request('POST', '/cart/add', [
            'product_id' => 11,
            'size' => 'M',
        ]);
        
        $this->assertResponseRedirects('/products');
        
        // Simuler le checkout
        $client->request('GET', '/cart/checkout');
        
        // Vérifier que Stripe nous redirige vers une URL de paiement
        $this->assertResponseRedirects();
        
        // Vérifier que la redirection contient 'stripe.com'
        $location = $client->getResponse()->headers->get('location');
        $this->assertStringContainsString('stripe.com', $location);
    }

    public function testCheckoutSuccessPage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/cart/success');
        
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.alert', 'Paiement effectué avec succès');
    }

    public function testCheckoutCancelPage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/cart/cancel');
        
        $this->assertResponseRedirects('/cart');
    }
}