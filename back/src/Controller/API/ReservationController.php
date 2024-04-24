<?php

namespace App\Controller\API;

use App\Entity\Ecole;
use App\Entity\Product;
use App\Entity\Reservation;
use App\Entity\User;
use App\Repository\AtelierRepository;
use App\Repository\EcoleRepository;
use App\Repository\ProductRepository;
use App\Repository\ReservationRepository;
use App\Repository\UserRepository;
use App\Trait\ApiResponseTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;
#[Route('/reservation', name: 'app_reservation')]
class ReservationController extends AbstractController
{
    use ApiResponseTrait;

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    #[Route('/new', name: 'new_reservation', methods: ['POST'])]
    public function createReservation(Request $request, EntityManagerInterface $entityManager, AtelierRepository $atelierRepository, UserRepository $userRepository, ReservationRepository $reservationRepository): Response
    {
        $data = json_decode($request->getContent(), true);

        $user = $userRepository->find($data['user']);
        if (!$user) {
            throw new BadRequestHttpException('Invalid user.');
        }

        $atelier = $atelierRepository->find($data['atelier']);
        if (!$atelier) {
            throw new BadRequestHttpException('Invalid atelier.');
        }

        if ($atelier->getEcole() && empty($data['classe'])) {
            return $this->createApiResponse([], 'Atelier with school requires a class.', Response::HTTP_BAD_REQUEST);
        }

        if ($reservationRepository->hasUserAlreadyReservedAtelier($data['user'], $data['atelier'])) {
            return $this->createApiResponse([], 'User has already reserved this atelier.', Response::HTTP_BAD_REQUEST);
        }

        $currentTotalParticipants = $reservationRepository->countTotalParticipantsForAtelier($atelier->getId());
        $proposedTotalParticipants = $currentTotalParticipants + $data['nombre_participant'];

        if ($proposedTotalParticipants > $atelier->getLimiteParticipant()) {
            return $this->createApiResponse([], 'This reservation exceeds the participant limit for the atelier.', Response::HTTP_BAD_REQUEST);
        }

        $reservation = new Reservation();

        $reservation->addUser($user);
        $reservation->addAtelier($atelier);
        $reservation->setIsPaid(false);
        $reservation->setNombre($data['nombre_participant']);

        if (!empty($data['classe'])) {
            $reservation->setClasse($data['classe']);
        } else {
            $reservation->setClasse(null);
        }

        $entityManager->persist($reservation);
        $entityManager->flush();

        return $this->createApiResponse(['reservationId' => $reservation->getId()], 'Reservation created.', Response::HTTP_CREATED);
    }

    #[Route('/paid/{id}', name: 'payement', methods: ['PUT'])]
    public function Payement(ReservationRepository $reservationRepository, int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $reservation = $reservationRepository->find($id);

        if (!$reservation) {
            return $this->createApiResponse([], 'Reservation not Found.', Response::HTTP_BAD_REQUEST);
        }

        $reservation->setIsPaid(true );
        $entityManager->flush();

        return $this->createApiResponse(['message'=> 'Reservation updated successfully'],  Response::HTTP_OK);
    }

    #[Route('/show/{idAtelier}', name: 'show_by_atelier', methods: ['GET'])]
    public function showByAtelier(int $idAtelier, AtelierRepository $atelierRepository): Response
    {
        $atelier = $atelierRepository->find($idAtelier);

        if (!$atelier) {
            throw new NotFoundHttpException('Atelier not found');
        }

        $reservations = $atelier->getReservations();
        $usersSet = [];

        foreach ($reservations as $reservation) {
            foreach ($reservation->getUser() as $user) {
                $userId = $user->getId();
                if (!isset($usersSet[$userId])) {
                    $usersSet[$userId] = [
                        'userId' => $userId,
                        'reservationId' => $reservation->getId(),
                        'name' => $user->getNom(),
                        'email' => $user->getMail(),
                        'nombre_participant' => $reservation->getNombre(),
                        'classe' => $reservation->getClasse()
                    ];
                }
            }
        }

        return $this->createApiResponse(['users' => array_values($usersSet)], 'Users registered for the atelier through reservations.', Response::HTTP_OK);
    }

    #[Route('/delete/{reservationId}/{userId}', name: 'delete_user_from_reservation', methods: ['DELETE'])]
    public function deleteUserFromReservation(int $reservationId, int $userId, ReservationRepository $reservationRepository, EntityManagerInterface $entityManager): Response
    {
        $reservation = $reservationRepository->find($reservationId);
        if (!$reservation) {
            throw new NotFoundHttpException('Reservation not found');
        }

        $user = $reservation->getUser()->filter(function($u) use ($userId) {
            return $u->getId() === $userId;
        })->first();

        if (!$user) {
            return $this->createApiResponse([], 'User not found in this reservation.', Response::HTTP_NOT_FOUND);
        }

        $reservation->removeUser($user);

        if ($reservation->getUser()->isEmpty()) {
            foreach ($reservation->getAteliers() as $atelier) {
                $reservation->removeAtelier($atelier);
            }

            $entityManager->remove($reservation);
        }

        $entityManager->flush();

        return $this->createApiResponse([], 'User removed from the reservation.', Response::HTTP_OK);
    }

    #[Route('/create-with-ateliers', name: 'create_user_with_ateliers', methods: ['POST'])]
    public function createUserWithAteliers(Request $request, UserRepository $userRepository, AtelierRepository $atelierRepository, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        $user = new User();
        $user->setNom($data['nom']);
        $user->setPrenom($data['prenom']);
        $user->setMail($data['mail']);
        $user->setRole('ROLE_USER');
        $user->setPassword($passwordHasher->hashPassword($user, '123456789'));
        if (isset($data['telephone'])) {
            $user->setNumeroTelephone($data['telephone']);
        }

        $entityManager->persist($user);

        foreach ($data['ateliersId'] as $atelierId) {
            $atelier = $atelierRepository->find($atelierId);
            if ($atelier) {
                $reservation = new Reservation();
                $reservation->addUser($user);
                $reservation->addAtelier($atelier);
                $reservation->setNombre(1);
                $reservation->setIsPaid(false);

                $entityManager->persist($reservation);
            }
        }

        $entityManager->flush();

        return $this->createApiResponse(['userId' => $user->getId()], 'User and reservations created.', Response::HTTP_CREATED);
    }


}