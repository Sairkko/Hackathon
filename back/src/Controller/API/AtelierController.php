<?php

namespace App\Controller\API;

use App\Entity\Atelier;
use App\Entity\User;
use App\Repository\AtelierRepository;
use App\Repository\UserRepository;
use App\Trait\ApiResponseTrait;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function atelier(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        $requiredFields = ['date_debut', 'date_fin', 'limite_participant'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                return $this->json(['message' => sprintf('Missing required field: %s.', $field)], Response::HTTP_BAD_REQUEST);
            }
        }

        $atelier = new Atelier();
        $atelier->setDateDebut(new \DateTime($data['date_debut']));
        $atelier->setDateFin(new \DateTime($data['date_fin']));
        $atelier->setLimiteParticipant($data['limite_participant']);
        $atelier->setLocalisation($data['localisation'] ?? null);
        $atelier->setThematique($data['thematique'] ?? null);

        $entityManager->persist($atelier);
        $entityManager->flush();

        return $this->createApiResponse(['id' => $atelier->getId()], Response::HTTP_CREATED);
    }

    #[Route('/all', name: 'all', methods: ['GET'])]
    public function allAtelier(EntityManagerInterface $entityManager): Response
    {
        $ateliers = $entityManager->getRepository(Atelier::class)->findAll();

        $ateliersArray = [];
        foreach ($ateliers as $atelier) {
            $ateliersArray[] = [
                'id' => $atelier->getId(),
                'date_debut' => $atelier->getDateDebut()->format('Y-m-d H:i:s'),
                'date_fin' => $atelier->getDateFin()->format('Y-m-d H:i:s'),
                'limite_participant' => $atelier->getLimiteParticipant(),
                'localisation' => $atelier->getLocalisation(),
                'thematique' => $atelier->getThematique()
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

        $atelierArray = [
            'id' => $atelier->getId(),
            'date_debut' => $atelier->getDateDebut()->format('Y-m-d H:i:s'),
            'date_fin' => $atelier->getDateFin()->format('Y-m-d H:i:s'),
            'limite_participant' => $atelier->getLimiteParticipant(),
            'localisation' => $atelier->getLocalisation(),
            'thematique' => $atelier->getThematique(),
        ];

        return $this->createApiResponse($atelierArray, Response::HTTP_OK);
    }

    #[Route('/delete/{id}', name: 'delete_atelier_by_id', methods: ['DELETE'])]
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
    public function editProduct(int $id, Request $request, AtelierRepository $atelierRepository, EntityManagerInterface $entityManager): Response
    {
        $atelier = $atelierRepository->find($id);

        if (!$atelier) {
            return $this->createApiResponse([], 'Atelier not Found.', Response::HTTP_BAD_REQUEST);
        }

        $data = json_decode($request->getContent(), true);

        if (!empty($data['date_debut'])) {
            $atelier->setDateDebut(new \DateTime($data['date_debut']));
        }

        if (!empty($data['date_fin'])) {
            $atelier->setDateFin(new \DateTime($data['date_fin']));
        }

        $atelier->setLimiteParticipant($data['limite_participant'] ?? $atelier->getLimiteParticipant());
        $atelier->setLocalisation($data['localisation'] ?? $atelier->getLocalisation());
        $atelier->setThematique($data['thematique'] ?? $atelier->getThematique());

        $entityManager->flush();

        return $this->createApiResponse(['message'=> 'Atelier updated successfully'],  Response::HTTP_OK);
    }
}
