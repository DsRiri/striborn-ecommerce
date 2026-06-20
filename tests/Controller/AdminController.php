<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $products = $entityManager->getRepository(Product::class)->findAll();

        return $this->render('admin/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/admin/product/delete/{id}', name: 'app_admin_product_delete')]
    public function delete(Product $product, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $entityManager->remove($product);
        $entityManager->flush();

        $this->addFlash('success', 'Produit supprimé !');
        return $this->redirectToRoute('app_admin');
    }

    /**
     * @Route("/admin/product/add", name="app_admin_product_add")
     */
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($request->isMethod('POST')) {
            $product = new Product();
            $product->setName($request->request->get('name'));
            $product->setPrice($request->request->get('price'));
            $product->setSize('XL');
            $product->setStock($request->request->get('stock'));
            $product->setIsFeatured(false);
            $product->setImage('default.jpg');

            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash('success', 'Produit ajouté !');
            return $this->redirectToRoute('app_admin');
        }

        return $this->redirectToRoute('app_admin');
    }

    /**
     * @Route("/admin/product/edit/{id}", name="app_admin_product_edit")
     */
    public function edit(Product $product, Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($request->isMethod('POST')) {
            $product->setName($request->request->get('name'));
            $product->setPrice($request->request->get('price'));
            $product->setStock($request->request->get('stock'));

            $entityManager->flush();

            $this->addFlash('success', 'Produit modifié !');
            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/edit.html.twig', [
            'product' => $product,
        ]);
    }
}