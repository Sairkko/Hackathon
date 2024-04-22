<?php

namespace App\Controller\API;

use App\Entity\Ecole;
use App\Entity\Product;
use App\Entity\Reservation;
use App\Entity\User;
use App\Repository\EcoleRepository;
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
#[Route('/reservation', name: 'app_resrevation_')]
class ReservationController extends AbstractController
{
    use ApiResponseTrait;

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    #[Route('/new', name: 'new', methods: ['POST'])]
    public function createReservation(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        $requiredFields = ['nom'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                return $this->createApiResponse([], sprintf('Missing required field: %s.', $field), Response::HTTP_BAD_REQUEST);
            }
        }

        $school= new Reservation();
        $school->addUser($data['nom']);
        $school->addAtelier($data['nom']);

        $entityManager->persist($school);
        $entityManager->flush();

        return $this->createApiResponse(['school' => ['id' => $school->getId()]], 'School created.', Response::HTTP_CREATED);
    }

}