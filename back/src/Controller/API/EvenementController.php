<?php

namespace App\Controller\API;

use App\Entity\Atelier;
use App\Entity\Reservation;
use App\Entity\User;
use App\Repository\AtelierContentRepository;
use App\Repository\AtelierRepository;
use App\Repository\EcoleRepository;
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

#[Route('/evenement', name: 'app_evenement_')]
class EvenementController extends AbstractController
{
    use ApiResponseTrait;

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    /**
     * @throws Exception
     */
    #[Route('/new', name: 'new', methods: ['POST'])]
    public function atelier(AtelierContentRepository $atelierContentRepository, EcoleRepository $ecoleRepository ,Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        $requiredFields = ['date_debut', 'date_fin', 'limite_participant', 'atelier'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                return $this->json(['message' => sprintf('Missing required field: %s.', $field)], Response::HTTP_BAD_REQUEST);
            }
        }

        $dateDebut = !empty($data['date_debut']) ? new \DateTime($data['date_debut']) : "";
        $dateFin = !empty($data['date_fin']) ? new \DateTime($data['date_fin']) : "";

        if ($dateDebut > $dateFin) {
            return $this->createApiResponse([], 'erreur : Date de fin avant date de debut.', Response::HTTP_BAD_REQUEST);
        }

        $atelier = new Atelier();
        $atelier->setDateDebut(new \DateTime($data['date_debut']));
        $atelier->setDateFin(new \DateTime($data['date_fin']));
        $atelier->setLimiteParticipant($data['limite_participant']);
        $atelier->setLocalisation($data['localisation'] ?? null);
        if (!empty($data['date_inscription_maximum'])) {
            $dateInscriptionMaximum = new \DateTime($data['date_inscription_maximum']);
        } else {
            $dateInscriptionMaximum = (clone new \DateTime($data['date_debut']))->modify('-1 week');
        }
        $atelier->setDateInscriptionMaximum($dateInscriptionMaximum);

        if (isset($data['school'])) {
            $ecole = $ecoleRepository->find($data['school']);
            if (!$ecole) {
                return $this->createApiResponse([], 'Ecole not found', Response::HTTP_BAD_REQUEST);
            }
            $atelier->setEcole($ecole);
        } else {
            $atelier->setEcole(null);
        }

        if (isset($data['atelier'])) {
            $atelierContent = $atelierContentRepository->find($data['atelier']);
            if (!$atelierContent) {
                return $this->createApiResponse([], 'Atelier Content not found', Response::HTTP_BAD_REQUEST);
            }
            $atelier->setAtelierContent($atelierContent);
        }

        $entityManager->persist($atelier);
        $entityManager->flush();

        $newEvent = [
            'id' => $atelier->getId(),
            'date_debut' => $atelier->getDateDebut(),
            'date_fin' => $atelier->getDateFin(),
            'date_inscription_maximum' => $atelier->getDateInscriptionMaximum(),
            'limite_participant' => $atelier->getLimiteParticipant(),
            'localisation' => $atelier->getLocalisation(),
            'ecole' => $atelier->getEcole() ? $atelier->getEcole()->getId(): null,
            'atelier' => [
                "id" => $atelier->getAtelierContent()->getId(),
                "name" => $atelier->getAtelierContent()->getNom()
            ]
        ];

        return $this->createApiResponse($newEvent, Response::HTTP_CREATED);
    }

    #[Route('/all', name: 'all', methods: ['GET'])]
    public function allAtelier(EntityManagerInterface $entityManager): Response
    {
        $ateliers = $entityManager->getRepository(Atelier::class)->findAll();

        $ateliersArray = [];
        foreach ($ateliers as $atelier) {
            $ecoleData = $atelier->getEcole();
            $atelierData = $atelier->getAtelierContent();
            $ateliersArray[] = [
                'id' => $atelier->getId(),
                'date_debut' => $atelier->getDateDebut(),
                'date_fin' => $atelier->getDateFin(),
                'date_inscription_maximum' => $atelier->getDateInscriptionMaximum(),
                'limite_participant' => $atelier->getLimiteParticipant(),
                'localisation' => $atelier->getLocalisation(),
                'ecole' => $ecoleData ? [
                    'id' => $ecoleData->getId(),
                    'nom' => $ecoleData->getNom()
                ] : null,
                'atelier' => $atelierData ? [
                    'id' => $atelierData->getId(),
                    'nom' => $atelierData->getNom()
                ] : null,
            ];
        }

        return $this->createApiResponse($ateliersArray, Response::HTTP_OK);
    }

    #[Route('/one/{id}', name: 'get_atelier_by_id', methods: ['GET'])]
    public function getOneAtelier(int $id, EntityManagerInterface $entityManager): Response
    {
        $atelier = $entityManager->getRepository(Atelier::class)->find($id);

        if (!$atelier) {
            throw new NotFoundHttpException('Atelier not found.');
        }

        $ecoleData = $atelier->getEcole();
        $atelierContentData = $atelier->getAtelierContent();
        $reservations = $atelier->getReservations();

        $reservationsArray = [];
        foreach ($reservations as $reservation) {
            $usersArray = [];
            foreach ($reservation->getUser() as $user) {
                $usersArray[] = [
                    'id' => $user->getId(),
                    'nom' => $user->getNom()
                ];
            }
            $reservationsArray[] = [
                'id' => $reservation->getId(),
                'classe' => $reservation->getClasse(),
                'nombre' => $reservation->getNombre(),
                'isPaid' => $reservation->isIsPaid(),
                'users' => $usersArray
            ];
        }

        $atelierArray = [
            'id' => $atelier->getId(),
            'date_debut' => $atelier->getDateDebut(),
            'date_fin' => $atelier->getDateFin(),
            'date_inscription_maximum' => $atelier->getDateInscriptionMaximum(),
            'limite_participant' => $atelier->getLimiteParticipant(),
            'localisation' => $atelier->getLocalisation(),
            'ecole' => $ecoleData ? [
                'id' => $ecoleData->getId(),
                'nom' => $ecoleData->getNom()
            ] : null,
            'atelierContent' => $atelierContentData ? [
                'id' => $atelierContentData->getId(),
                'nom' => $atelierContentData->getNom()
            ] : null,
            'reservations' => $reservationsArray
        ];

        return $this->createApiResponse($atelierArray, Response::HTTP_OK);
    }


    #[Route('/delete/{id}', name: 'delete_evenement_by_id', methods: ['DELETE'])]
    public function deleteOneAtelier(int $id, EntityManagerInterface $entityManager): Response
    {
        $atelier = $entityManager->getRepository(Atelier::class)->find($id);

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
    public function editAtelier(EcoleRepository $ecoleRepository, AtelierContentRepository $atelierContentRepository, int $id, Request $request, AtelierRepository $atelierRepository, EntityManagerInterface $entityManager): Response
    {
        $atelier = $atelierRepository->find($id);

        if (!$atelier) {
            return $this->createApiResponse([], 'Atelier not Found.', Response::HTTP_BAD_REQUEST);
        }

        $data = json_decode($request->getContent(), true);

        $dateDebut = !empty($data['date_debut']) ? new \DateTime($data['date_debut']) : $atelier->getDateDebut();
        $dateFin = !empty($data['date_fin']) ? new \DateTime($data['date_fin']) : $atelier->getDateFin();

        if ($dateDebut > $dateFin) {
            return $this->createApiResponse([], 'Erreur: Date de fin avant date de debut.', Response::HTTP_BAD_REQUEST);
        }

        $atelier->setDateDebut($dateDebut);
        $atelier->setDateFin($dateFin);
        $atelier->setLimiteParticipant($data['limite_participant'] ?? $atelier->getLimiteParticipant());
        $atelier->setLocalisation($data['localisation'] ?? $atelier->getLocalisation());

        if (!empty($data['date_inscription_maximum'])) {
            $atelier->setDateInscriptionMaximum(new \DateTime($data['date_inscription_maximum']));
        }

        if (isset($data['school'])) {
            $ecole = $ecoleRepository->find($data['school']);
            if (!$ecole) {
                return $this->createApiResponse([], 'Ecole not found', Response::HTTP_BAD_REQUEST);
            }
            $atelier->setEcole($ecole);
        }

        if (isset($data['atelier'])) {
            $atelierContent = $atelierContentRepository->find($data['atelier']);
            if (!$atelierContent) {
                return $this->createApiResponse([], 'Atelier Content not found', Response::HTTP_BAD_REQUEST);
            }
            $atelier->setAtelierContent($atelierContent);
        }

        $entityManager->flush();

        return $this->createApiResponse(['message'=> 'Atelier updated successfully'], Response::HTTP_OK);
    }

    #[Route('/all/user/{userId}', name: 'get_evenement_by_user', methods: ['GET'])]
    public function getUserAteliers(int $userId, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->find($userId);
        if (!$user) {
            throw new NotFoundHttpException('User not found.');
        }

        $reservations = $user->getReservations();
        if ($reservations->isEmpty()) {
            throw new NotFoundHttpException('No reservations found for this user.');
        }

        $ateliers = [];
        $reservationsArray = [];
        foreach ($reservations as $reservation) {
            foreach ($reservation->getAteliers() as $atelier) {
                $atelierContent = $atelier->getAtelierContent();
                $ateliers[] = [
                    'id' => $atelier->getId(),
                    'date_debut' => $atelier->getDateDebut() ? $atelier->getDateDebut() : null,
                    'date_fin' => $atelier->getDateFin() ? $atelier->getDateFin() : null,
                    'date_inscription_maximum' => $atelier->getDateInscriptionMaximum() ? $atelier->getDateInscriptionMaximum() : null,
                    'localisation' => $atelier->getLocalisation(),
                    'ecole' => $atelier->getEcole() ? [
                        'id' => $atelier->getEcole()->getId(),
                        'nom' => $atelier->getEcole()->getNom()
                    ] : null,
                    'atelier_content' => $atelierContent ? [
                        'id' => $atelierContent->getId(),
                        'nom' => $atelierContent->getNom()
                    ] : null,
                    'reservation' => [
                        'id' => $reservation->getId(),
                        'is_paid' => $reservation->isIsPaid(),
                    ]
                ];
            }
        }

        if (empty($ateliers)) {
            throw new NotFoundHttpException('No ateliers found associated with the user\'s reservations.');
        }

        return $this->createApiResponse($ateliers, Response::HTTP_OK);
    }
}
