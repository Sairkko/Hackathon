<?php

namespace App\Controller\API;

use App\Entity\User;
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
class LoginController extends AbstractController
{
    use ApiResponseTrait;

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    #[Route('/register', name: 'app_register', methods: ['POST'])]
    public function register(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $data = json_decode($request->getContent(), true);

        $requiredFields = ['firstName', 'lastName', 'email', 'role', 'password'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                return $this->createApiResponse([], sprintf('Missing required field: %s.', $field), Response::HTTP_BAD_REQUEST);
            }
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return $this->createApiResponse([], 'Invalid email provided.', Response::HTTP_BAD_REQUEST);
        }

        $user = new User();
        $user->setPrenom($data['firstName']);
        $user->setNom($data['lastName']);
        $user->setNumeroTelephone($data['phoneNumber'] ?? null);
        $user->setMail($data['email']);
        $user->setRole('ROLE_USER');
        $user->setPassword($passwordHasher->hashPassword($user, $data['password']));

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->createApiResponse(['user' => ['id' => $user->getId()]], 'User registered successfully.', Response::HTTP_CREATED);
    }

    #[Route('/login', name: 'app_login', methods: ['POST'])]
    public function login(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        $user = $userRepository->findOneBy(['mail' => $email]);

        if (!$user || !$passwordHasher->isPasswordValid($user, $password)) {
            return $this->createApiResponse([], 'Invalid credentials.', Response::HTTP_UNAUTHORIZED);
        }

        $token = bin2hex(random_bytes(32));

        return $this->createApiResponse(['token' => $token], 'Login successful.', Response::HTTP_OK);
    }


}