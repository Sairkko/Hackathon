<?php

namespace App\Controller\API;

use App\Entity\AboutPage;
use App\Entity\AtelierContent;
use App\Repository\AboutPageRepository;
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

#[Route('/about', name: 'app_atelier_')]
class AboutPageController extends AbstractController
{
    use ApiResponseTrait;

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    /**
     * @throws Exception
     */
    #[Route('/edit', name: 'edit_about_page', methods: ['PUT'])]
    public function editProduct(Request $request, AboutPageRepository $aboutPageRepository, EntityManagerInterface $entityManager): Response
    {
        $about = $aboutPageRepository->find(1);

        if (!$about) {
            return $this->createApiResponse([], 'Content about not Found.', Response::HTTP_BAD_REQUEST);
        }

        $data = json_decode($request->getContent(), true);

        $about->setTitle($data['title'] ?? $about->getTitle());
        $about->setDescription($data['description'] ?? $about->getDescription());
        $about->setTitleSpan($data['span'] ?? $about->getTitleSpan());

        $entityManager->flush();

        return $this->createApiResponse(['message'=> 'Atelier updated successfully'],  Response::HTTP_OK);
    }
    #[Route('/content', name: 'get_content', methods: ['GET'])]
    public function getContent(EntityManagerInterface $entityManager): Response
    {
        // Récupère la première entrée trouvée dans la base de données
        $about = $entityManager->getRepository(AboutPage::class)->findOneBy([]);

        if (!$about) {
            throw new NotFoundHttpException('Content about not found.');
        }

        $aboutArray = [
            'id' => $about->getId(),
            'title' => $about->getTitle(),
            'span' => $about->getTitleSpan(),
            'description' => $about->getDescription(),
        ];

        return $this->createApiResponse($aboutArray, Response::HTTP_OK);
    }

}
