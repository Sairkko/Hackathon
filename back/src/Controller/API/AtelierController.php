<?php

namespace App\Controller\API;

use App\Entity\Atelier;
use App\Entity\AtelierContent;
use App\Entity\Reservation;
use App\Entity\User;
use App\Repository\AtelierContentRepository;
use App\Repository\AtelierRepository;
use App\Repository\EcoleRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use App\Trait\ApiResponseTrait;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/atelier', name: 'app_atelier_')]
class AtelierController extends AbstractController
{
    use ApiResponseTrait;

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    /**
     * @throws Exception
     */
    #[Route('/new', name: 'new', methods: ['POST'])]
    public function atelier(ProductRepository $productRepository ,Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        $requiredFields = ['prix', 'nom', 'description'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                return $this->json(['message' => sprintf('Missing required field: %s.', $field)], Response::HTTP_BAD_REQUEST);
            }
        }

        $atelier = new AtelierContent();
        $atelier->setNom($data['nom']);
        $atelier->setDescription($data['description']);
        $atelier->setPrix($data['prix']);
        $atelier->setThematique($data['thematique'] ?? null);

        if (!empty($data['products']) && is_array($data['products'])) {
            foreach ($data['products'] as $productId) {
                $product = $productRepository->find($productId);
                if ($product) {
                    $atelier->addProduct($product);
                } else {
                    return $this->json(['message' => sprintf('Product with ID %d not found.', $productId)], Response::HTTP_NOT_FOUND);
                }
            }
        }


        $entityManager->persist($atelier);
        $entityManager->flush();

        return $this->createApiResponse([], Response::HTTP_CREATED);
    }

    #[Route('/all', name: 'all', methods: ['GET'])]
    public function allAtelier(EntityManagerInterface $entityManager): Response
    {
        $ateliers = $entityManager->getRepository(AtelierContent::class)->findAll();

        $ateliersArray = [];
        foreach ($ateliers as $atelier) {
            $productDetails = [];
            foreach ($atelier->getProducts() as $product) {
                $productDetails[] = [
                    'nom' => $product->getNom(),
                    'cepage' => $product->getCepage()
                ];
            }

            $ateliersArray[] = [
                'id' => $atelier->getId(),
                'thematique' => $atelier->getThematique(),
                'products' => $productDetails,
                'nom' => $atelier->getNom(),
                'description' => $atelier->getDescription(),
                'prix' => $atelier->getPrix(),
            ];
        }

        return $this->createApiResponse($ateliersArray, Response::HTTP_OK);
    }

    #[Route('/delete/{id}', name: 'delete_atelier_by_id', methods: ['DELETE'])]
    public function deleteOneAtelier(int $id, EntityManagerInterface $entityManager): Response
    {
        $atelier = $entityManager->getRepository(AtelierContent::class)->find($id);

        if (!$atelier) {
            throw new NotFoundHttpException('Atelier not found.');
        }

        $entityManager->remove($atelier);
        $entityManager->flush();

        return $this->createApiResponse(['message' => 'Atelier deleted successfully.'], Response::HTTP_OK);
    }

    /**
     * @throws Exception
     */

    #[Route('/edit/{id}', name: 'edit_atelier', methods: ['PUT'])]
    public function editProduct(ProductRepository $productRepository, int $id, Request $request, AtelierContentRepository $atelierRepository, EntityManagerInterface $entityManager): Response
    {
        $atelier = $atelierRepository->find($id);

        if (!$atelier) {
            return $this->createApiResponse([], 'Atelier not Found.', Response::HTTP_BAD_REQUEST);
        }

        $data = json_decode($request->getContent(), true);

        $atelier->setThematique($data['thematique'] ?? $atelier->getThematique());
        $atelier->setNom($data['nom'] ?? $atelier->getNom());
        $atelier->setDescription($data['description'] ?? $atelier->getDescription());
        $atelier->setPrix($data['prix'] ?? $atelier->getPrix());

        if (isset($data['products']) && is_array($data['products'])) {
            $currentProducts = $atelier->getProducts();
            foreach ($currentProducts as $product) {
                $atelier->removeProduct($product);
            }

            foreach ($data['products'] as $productId) {
                $product = $productRepository->find($productId);
                if ($product) {
                    $atelier->addProduct($product);
                } else {
                    return $this->createApiResponse(['message' => sprintf('Product with ID %d not found.', $productId)], Response::HTTP_NOT_FOUND);
                }
            }
        }

        $entityManager->flush();

        return $this->createApiResponse(['message'=> 'Atelier updated successfully'],  Response::HTTP_OK);
    }
}
