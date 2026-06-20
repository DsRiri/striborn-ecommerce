<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/products", name="app_products")
     */
    public function index(Request $request, ProductRepository $productRepository): Response
    {
        $priceFilter = $request->query->get('price_filter');
        $products = $productRepository->findAll();

        if ($priceFilter) {
            switch ($priceFilter) {
                case '10-29':
                    $products = $productRepository->findByPriceRange(10, 29);
                    break;
                case '29-35':
                    $products = $productRepository->findByPriceRange(29, 35);
                    break;
                case '35-50':
                    $products = $productRepository->findByPriceRange(35, 50);
                    break;
            }
        }

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'currentFilter' => $priceFilter,
        ]);
    }

    /**
     * @Route("/product/{id}", name="app_product_show")
     */
    public function show(Product $product): Response
    {
        $sizes = ['XS', 'S', 'M', 'L', 'XL'];
        
        return $this->render('product/show.html.twig', [
            'product' => $product,
            'sizes' => $sizes,
        ]);
    }
}