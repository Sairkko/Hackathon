<?php

namespace App\Controller\API;

use App\Entity\Product;
use App\Entity\User;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use App\Trait\ApiResponseTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;
#[Route('/product', name: 'app_product_')]
class ProductController extends AbstractController
{
    use ApiResponseTrait;

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    #[Route('/new', name: 'new', methods: ['POST'])]
    public function createProduct(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        $requiredFields = ['region', 'millesime', 'cepage', 'nom', 'type', 'volume'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                return $this->createApiResponse([], sprintf('Missing required field: %s.', $field), Response::HTTP_BAD_REQUEST);
            }
        }

        $product = new Product();
        $product->setRegion($data['region']);
        $product->setNom($data['nom']);
        $product->setVolume($data['volume']);
        $product->setStock($data['stock'] ?? 0);
        $product->setCepage($data['cepage']);
        $product->setMillesime($data['millesime']);
        $product->setType($data['type']);
        $product->setDescription($data['type'] ?? "");

        $entityManager->persist($product);
        $entityManager->flush();

        return $this->createApiResponse([], 'Product created.', Response::HTTP_CREATED);
    }

    #[Route('/all', name: 'get_all_products', methods: ['GET'])]
    public function getAllProducts(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();

        $productData = array_map(function ($product) {
            return [
                'id' => $product->getId(),
                'name' => $product->getNom(),
                'region' => $product->getRegion(),
                'millesime' => $product->getMillesime(),
                'cepage' => $product->getCepage(),
                'stock' => $product->getStock(),
                'type' => $product->getType(),
                'volume' => $product->getVolume(),
                'description' => $product->getDescription(),
            ];
        }, $products);

        return $this->createApiResponse($productData, 'All products retrieved.', Response::HTTP_OK);
    }

    #[Route('/one/{id}', name: 'get_product_by_id', methods: ['GET'])]
    public function getOneProduct(int $id, ProductRepository $productRepository): Response
    {
        $product = $productRepository->find($id);

        if (!$product) {
            return $this->createApiResponse([], 'Product not Found.', Response::HTTP_BAD_REQUEST);
        }

        $productData = [
            'id' => $product->getId(),
            'name' => $product->getNom(),
            'region' => $product->getRegion(),
            'millesime' => $product->getMillesime(),
            'cepage' => $product->getCepage(),
            'stock' => $product->getStock(),
            'volume' => $product->getVolume(),
            'type' => $product->getType(),
            'description' => $product->getDescription(),
        ];

        return $this->createApiResponse($productData, 'Product retrieved.', Response::HTTP_OK);
    }

    #[Route('/delete/{id}', name: 'delete_product', methods: ['DELETE'])]
    public function deleteProduct(int $id, ProductRepository $productRepository, EntityManagerInterface $entityManager): Response
    {
        $product = $productRepository->find($id);

        if (!$product) {
            return $this->createApiResponse([], 'Product not Found.', Response::HTTP_BAD_REQUEST);
        }

        $entityManager->remove($product);
        $entityManager->flush();

        return $this->createApiResponse([], 'Product deleted successfully.', Response::HTTP_OK);
    }

    #[Route('/edit/{id}', name: 'edit_product', methods: ['PUT'])]
    public function editProduct(int $id, Request $request, ProductRepository $productRepository, EntityManagerInterface $entityManager): Response
    {
        $product = $productRepository->find($id);

        if (!$product) {
            return $this->createApiResponse([], 'Product not Found.', Response::HTTP_BAD_REQUEST);
        }

        $data = json_decode($request->getContent(), true);

        $product->setNom($data['name'] ?? $product->getNom());
        $product->setVolume($data['volume'] ?? $product->getVolume());
        $product->setRegion($data['region'] ?? $product->getRegion());
        $product->setMillesime($data['millesime'] ?? $product->getMillesime());
        $product->setCepage($data['cepage'] ?? $product->getCepage());
        $product->setStock($data['stock'] ?? $product->getStock());
        $product->setType($data['type'] ?? $product->getType());
        $product->setDescription($data['description'] ?? $product->getDescription());

        $entityManager->flush();

        return $this->createApiResponse([], 'Product updated successfully.', Response::HTTP_OK);
    }

}