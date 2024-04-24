<?php

namespace App\Controller\API;

use App\Entity\User;
use App\Repository\ReservationRepository;
use App\Repository\UserRepository;
use App\Trait\ApiResponseTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;
#[Route('/user', name: 'app_user')]
class UserController extends AbstractController
{
    use ApiResponseTrait;

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    #[Route('/edit/{id}', name: 'edit', methods: ['PUT'])]
    public function editUser(int $id, Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = $userRepository->find($id);
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['nom'])) {
            $user->setNom($data['nom']);
        }
        if (isset($data['prenom'])) {
            $user->setPrenom($data['prenom']);
        }
        if (isset($data['mail'])) {
            $user->setMail($data['email']);
        }
        if (isset($data['telephone'])) {
            $user->setNumeroTelephone($data['telephone']);
        }

        if (!empty($data['password'])) {
            $user->setPassword($passwordHasher->hashPassword($user, $data['password']));
        }

        $entityManager->flush();

        return $this->createApiResponse(['userId' => $user->getId()], 'User updated successfully.', Response::HTTP_OK);
    }

    #[Route('/delete-user/{userId}', name: 'delete_user_and_reservations', methods: ['DELETE'])]
    public function deleteUserAndReservations(int $userId, UserRepository $userRepository, ReservationRepository $reservationRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $userRepository->find($userId);
        if (!$user) {
            throw new NotFoundHttpException('User not found');
        }

        $entityManager->remove($user);
        $entityManager->flush();

        return $this->createApiResponse([], 'User and all reservations removed.', Response::HTTP_OK);
    }


}