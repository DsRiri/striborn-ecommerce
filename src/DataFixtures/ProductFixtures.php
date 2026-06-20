<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $products = [
            ['name' => 'Blackbelt', 'size' => 'XL', 'price' => 29.90, 'stock' => 2, 'isFeatured' => true, 'image' => 'blackbelt.jpg'],
            ['name' => 'BlueBelt', 'size' => 'XL', 'price' => 29.90, 'stock' => 2, 'isFeatured' => false, 'image' => 'bluebelt.jpg'],
            ['name' => 'Street', 'size' => 'XL', 'price' => 34.50, 'stock' => 2, 'isFeatured' => false, 'image' => 'street.jpg'],
            ['name' => 'Pokeball', 'size' => 'XL', 'price' => 45.00, 'stock' => 2, 'isFeatured' => true, 'image' => 'pokeball.jpg'],
            ['name' => 'PinkLady', 'size' => 'XL', 'price' => 29.90, 'stock' => 2, 'isFeatured' => false, 'image' => 'pinklady.jpg'],
            ['name' => 'Snow', 'size' => 'XL', 'price' => 32.00, 'stock' => 2, 'isFeatured' => false, 'image' => 'snow.jpg'],
            ['name' => 'Greyback', 'size' => 'XL', 'price' => 28.50, 'stock' => 2, 'isFeatured' => false, 'image' => 'greyback.jpg'],
            ['name' => 'BlueCloud', 'size' => 'XL', 'price' => 45.00, 'stock' => 2, 'isFeatured' => false, 'image' => 'bluecloud.jpg'],
            ['name' => 'BornInUsa', 'size' => 'XL', 'price' => 59.90, 'stock' => 2, 'isFeatured' => true, 'image' => 'borninusa.jpg'],
            ['name' => 'GreenSchool', 'size' => 'XL', 'price' => 42.20, 'stock' => 2, 'isFeatured' => false, 'image' => 'greenschool.jpg'],
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