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
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;
#[Route('/reservation', name: 'app_reservation')]
class ReservationController extends AbstractController
{
    use ApiResponseTrait;
    private MailerInterface $mailer;

    public function __construct(private readonly EntityManagerInterface $entityManager, MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    #[Route('/new', name: 'new_reservation', methods: ['POST'])]
    public function createReservation(Request $request, EntityManagerInterface $entityManager, AtelierRepository $atelierRepository, UserRepository $userRepository, ReservationRepository $reservationRepository, MailerInterface $mailer): Response
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

        $atelierContent = $atelier->getAtelierContent();
        if (!$atelierContent || $atelierContent->getPrix() === null) {
            throw new BadRequestHttpException('Atelier price not set.');
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

        $totalPrice = $atelierContent->getPrix() * $data['nombre_participant'];

        $reservation = new Reservation();
        $reservation->addUser($user);
        $reservation->addAtelier($atelier);
        $reservation->setIsPaid(false);
        $reservation->setNombre($data['nombre_participant']);
        $reservation->setClasse($data['classe'] ?? null);

        $entityManager->persist($reservation);
        $entityManager->flush();

        $email = (new Email())
            ->from('hackathon@esgi.com')
            ->to($user->getMail())
            ->subject("Confirmation d'inscription à un atelier")
            ->html("<p>Pour confirmer votre inscription veuillez envoyer la somme : {$totalPrice}€ <br> Payer par PayPal à bonnetonolivier@gmail.com <br> Payer par Lydia ou Paylib: 06 83 05 90 70</p>");

        $mailer->send($email);

        return $this->createApiResponse(['reservationId' => $reservation->getId()], 'Reservation created.', Response::HTTP_CREATED);
    }


    #[Route('/paid/{id}', name: 'payment', methods: ['PUT'])]
    public function payment(UserRepository $userRepository, ReservationRepository $reservationRepository, int $id, Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $reservation = $reservationRepository->find($id);

        if (!$reservation) {
            return $this->createApiResponse([], 'Reservation not Found.', Response::HTTP_BAD_REQUEST);
        }

        $reservation->setIsPaid(true);
        $entityManager->flush();

        $users = $reservation->getUser();
        foreach ($users as $user) {
            $email = (new Email())
                ->from('hackathon@esgi.com')
                ->to($user->getMail()) // Assurez-vous que getMail retourne une adresse email valide
                ->subject("Confirmation d'inscription à un atelier")
                ->html("<p>Le paiement a bien été reçu, votre inscription est confirmée</p>");

            $mailer->send($email);
        }
        return $this->createApiResponse(['message' => 'Reservation updated and emails sent successfully'], Response::HTTP_OK);
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
                        'is_paid' => $reservation->isIsPaid(),
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
    public function createUserWithAteliers(Request $request, MailerInterface $mailer, AtelierRepository $atelierRepository, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
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
        $reservationId = null;

        $atelier = $atelierRepository->find($data['atelierId']);
        if ($atelier) {
            $reservation = new Reservation();
            $reservation->addUser($user);
            $reservation->addAtelier($atelier);
            $reservation->setNombre($data['nb_participants']);
            $reservation->setIsPaid(false);
        
            $entityManager->persist($reservation);
        }

        $entityManager->flush();

        $email = (new Email())
            ->from('hackathon@esgi.com')
            ->to($user->getMail())
            ->subject("Confirmation d'inscription à un atelier")
            ->html("<p>Votre compte a bien été créé vous pouvez vous connecter avec l'email suivant : {$user->getMail()} et ce mot de passe que vous pourrez changer : 123456789</p>");

        $mailer->send($email);

        $reservationId = $reservation->getId();


        $response = [
            'userId' => $user->getId(),
            'name' => $user->getNom(),
            'email' => $user->getMail(),
            'nombre_participant' => $data['nb_participants'],
            'is_paid' => false,
            'reservationId' => $reservationId,
        ];

        return $this->createApiResponse($response, 'User and reservations created.', Response::HTTP_CREATED);

    }

    #[Route('/relance/{id}', name: 'relance', methods: ['GET'])]
    public function relanceMail(UserRepository $userRepository, int $id, Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $user = $userRepository->find($id);

        if (!$user) {
            return $this->createApiResponse([], 'User not Found.', Response::HTTP_BAD_REQUEST);
        }

        $email = (new Email())
            ->from('hackathon@esgi.com')
            ->to($user->getMail())
            ->subject("Relance de payement")
            ->html("<p>Veuillez payer votre reservation afin d'etre inscrit definitivement</p>");

        $mailer->send($email);

        return $this->createApiResponse(['message' => 'Reservation updated and emails sent successfully'], Response::HTTP_OK);
    }

}