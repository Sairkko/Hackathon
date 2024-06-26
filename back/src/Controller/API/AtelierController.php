<?php

namespace App\Controller\API;

use App\Entity\AtelierContent;
use App\Repository\AtelierContentRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use App\Trait\ApiResponseTrait;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

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
                    'id' => $product->getId(),
                    'name' => $product->getNom(),
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

    #[Route('/one/{idAtelier}/{idUser}', name: 'get_atelier_content_by_id_user', methods: ['GET'])]
    public function oneAtelier(int $idAtelier, int $idUser, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $atelierContent = $entityManager->getRepository(AtelierContent::class)->find($idAtelier);
        if (!$atelierContent) {
            throw new NotFoundHttpException('AtelierContent not found.');
        }

        $user = $userRepository->find($idUser);
        if (!$user) {
            return $this->createApiResponse(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $productDetails = [];
        foreach ($atelierContent->getProducts() as $product) {
            $productDetails[] = [
                'nom' => $product->getNom(),
                'cepage' => $product->getCepage()
            ];
        }

        $ateliers = [];
        foreach ($atelierContent->getAteliers() as $atelier) {
            $isUserRegistered = false;
            foreach ($atelier->getReservations() as $reservation) {
                if ($reservation->getUser()->contains($user)) {
                    $isUserRegistered = true;
                    break;
                }
            }

            if (!$isUserRegistered) {
                $ateliers[] = [
                    'id' => $atelier->getId(),
                    'date_debut' => $atelier->getDateDebut() ? $atelier->getDateDebut()->format('Y-m-d H:i:s') : null,
                    'date_fin' => $atelier->getDateFin() ? $atelier->getDateFin()->format('Y-m-d H:i:s') : null,
                    'localisation' => $atelier->getLocalisation(),
                    'ecole' => $atelier->getEcole() ? [
                        'id' => $atelier->getEcole()->getId(),
                        'nom' => $atelier->getEcole()->getNom()
                    ] : null,
                ];
            }
        }

        $response = [
            'id' => $atelierContent->getId(),
            'thematique' => $atelierContent->getThematique(),
            'products' => $productDetails,
            'nom' => $atelierContent->getNom(),
            'description' => $atelierContent->getDescription(),
            'prix' => $atelierContent->getPrix(),
            'ateliers' => $ateliers
        ];

        return $this->createApiResponse($response, Response::HTTP_OK);
    }



    #[Route('/delete/{id}', name: 'delete_atelier_by_id', methods: ['DELETE'])]
    public function deleteOneAtelier(int $id, EntityManagerInterface $entityManager): Response
    {
        $atelier = $entityManager->getRepository(AtelierContent::class)->find($id);

        if (!$atelier) {
            throw new NotFoundHttpException('Atelier not found.');
        }

        foreach ($atelier->getAteliers() as $ateliers) {
            $entityManager->remove($ateliers);
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
