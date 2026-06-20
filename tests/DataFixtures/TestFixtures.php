<?php

namespace App\Tests\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TestFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Crea 3 prodotti per i test
        $products = [
            ['name' => 'Blackbelt', 'size' => 'XL', 'price' => 29.90, 'stock' => 2, 'isFeatured' => true, 'image' => 'blackbelt.jpg'],
            ['name' => 'Pokeball', 'size' => 'XL', 'price' => 45.00, 'stock' => 2, 'isFeatured' => true, 'image' => 'pokeball.jpg'],
            ['name' => 'BornInUsa', 'size' => 'XL', 'price' => 59.90, 'stock' => 2, 'isFeatured' => true, 'image' => 'borninusa.jpg'],
        ];

        foreach ($products as $productData) {
            $product = new Product();
            $product->setName($productData['name']);
            $product->setSize($productData['size']);
            $product->setPrice($productData['price']);
            $product->setStock($productData['stock']);
            $product->setIsFeatured($productData['isFeatured']);
            $product->setImage($productData['image']);
            $manager->persist($product);
        }

        $manager->flush();
    }
}