<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CartControllerTest extends WebTestCase
{
    public function testCartPageIsAccessible(): void
    {
        $client = static::createClient();
        $client->request('GET', '/cart');
        
        $this->assertResponseIsSuccessful();
    }

    public function testAddProductToCart(): void
    {
        $client = static::createClient();
        $client->request('GET', '/products');
        
        $this->assertResponseIsSuccessful();
        
        // Vérifier que le panier est vide au départ
        $client->request('GET', '/cart');
        $this->assertResponseIsSuccessful();
        
        // Ajouter un produit au panier
        $client->request('POST', '/cart/add', [
            'product_id' => 11,
            'size' => 'M',
        ]);
        
        $this->assertResponseRedirects('/products');
        
        // Vérifier que le produit a été ajouté
        $client->request('GET', '/cart');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('table', 'Blackbelt');
    }

    public function testRemoveProductFromCart(): void
    {
        $client = static::createClient();
        
        // Ajouter un produit
        $client->request('POST', '/cart/add', [
            'product_id' => 11,
            'size' => 'M',
        ]);
        
        // Aller au panier pour récupérer la clé
        $client->request('GET', '/cart');
        $this->assertResponseIsSuccessful();
        
        // Supprimer le produit (on simule la suppression)
        $client->request('GET', '/cart/remove/0');
        $this->assertResponseRedirects('/cart');
    }
}