<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\StripeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    private RequestStack $requestStack;
    private StripeService $stripeService;

    public function __construct(RequestStack $requestStack, StripeService $stripeService)
    {
        $this->requestStack = $requestStack;
        $this->stripeService = $stripeService;
    }

    /**
     * @Route("/cart", name="app_cart")
     */
    public function index(): Response
    {
        $session = $this->requestStack->getSession();
        $cart = $session->get('cart', []);
        $total = 0;

        // Ajouter quantity = 1 pour les anciens produits
        foreach ($cart as &$item) {
            if (!isset($item['quantity'])) {
                $item['quantity'] = 1;
            }
            $total += $item['price'] * $item['quantity'];
        }

        // Sauvegarder le panier mis à jour
        $session->set('cart', $cart);

        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
            'total' => $total,
            'stripe_public_key' => $this->stripeService->getPublishableKey(),
        ]);
    }

    /**
     * @Route("/cart/add", name="app_cart_add")
     */
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $productId = $request->request->get('product_id');
        $size = $request->request->get('size');
        $quantity = (int) $request->request->get('quantity', 1);

        $product = $entityManager->getRepository(Product::class)->find($productId);

        if (!$product) {
            $this->addFlash('error', 'Produit non trouvé');
            return $this->redirectToRoute('app_products');
        }

        $session = $this->requestStack->getSession();
        $cart = $session->get('cart', []);

        // Vérifier si le produit existe déjà (même id + même taille)
        $found = false;
        foreach ($cart as &$item) {
            if ($item['product_id'] == $productId && $item['size'] == $size) {
                $item['quantity'] += $quantity;
                $found = true;
                break;
            }
        }

        if (!$found) {
            $cart[] = [
                'product_id' => $product->getId(),
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'size' => $size,
                'quantity' => $quantity,
                'image' => $product->getImage(),
            ];
        }

        $session->set('cart', $cart);

        $this->addFlash('success', 'Produit ajouté au panier !');
        return $this->redirectToRoute('app_products');
    }

    /**
     * @Route("/cart/remove/{key}", name="app_cart_remove")
     */
    public function remove($key): Response
    {
        $session = $this->requestStack->getSession();
        $cart = $session->get('cart', []);

        if (isset($cart[$key])) {
            unset($cart[$key]);
            $session->set('cart', $cart);
            $this->addFlash('success', 'Produit retiré du panier !');
        }

        return $this->redirectToRoute('app_cart');
    }

    /**
     * @Route("/cart/checkout", name="app_cart_checkout")
     */
    public function checkout(Request $request): Response
    {
        $session = $this->requestStack->getSession();
        $cart = $session->get('cart', []);

        if (empty($cart)) {
            $this->addFlash('error', 'Votre panier est vide.');
            return $this->redirectToRoute('app_cart');
        }

        $checkoutSession = $this->stripeService->createCheckoutSession(
            $cart,
            $request->getSchemeAndHttpHost() . '/cart/success',
            $request->getSchemeAndHttpHost() . '/cart/cancel'
        );

        return $this->redirect($checkoutSession->url);
    }

    /**
     * @Route("/cart/success", name="app_cart_success")
     */
    public function success(): Response
    {
        $session = $this->requestStack->getSession();
        $session->remove('cart');

        $this->addFlash('success', 'Paiement effectué avec succès !');

        return $this->render('cart/success.html.twig');
    }

    /**
     * @Route("/cart/cancel", name="app_cart_cancel")
     */
    public function cancel(): Response
    {
        $this->addFlash('error', 'Le paiement a été annulé.');
        return $this->redirectToRoute('app_cart');
    }
}