<?php

namespace App\Controller\API;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Trait\ApiResponseTrait;
use Doctrine\ORM\EntityManagerInterface;
use Random\RandomException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;
class LoginController extends AbstractController
{
    private MailerInterface $mailer;
    use ApiResponseTrait;

    public function __construct(private readonly EntityManagerInterface $entityManager, MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    #[Route('/register', name: 'app_register', methods: ['POST'])]
    public function register(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $data = json_decode($request->getContent(), true);

        $requiredFields = ['firstName', 'lastName', 'email', 'password'];
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
    public function login(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $data = json_decode($request->getContent(), true);

        $requiredFields = ['email', 'password'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                return $this->createApiResponse([], sprintf('Missing required field: %s.', $field), Response::HTTP_BAD_REQUEST);
            }
        }
        $email = $data['email'];
        $password = $data['password'];

        $user = $userRepository->findOneBy(['mail' => $email]);

        if (!$user || !$passwordHasher->isPasswordValid($user, $password)) {
            return $this->createApiResponse([], 'Invalid credentials.', Response::HTTP_UNAUTHORIZED);
        }

        if (!$user->getToken()) {
            $token = bin2hex(random_bytes(32));
            $user->setToken($token);
            $entityManager->persist($user);
            $entityManager->flush();
        }

        $userData = [
            'id' => $user->getId(),
            'nom' => $user->getNom(),
            'prenom' => $user->getPrenom(),
            'token' => $user->getToken(),
            'telephone' => $user->getNumeroTelephone(),
            'mail' => $user->getMail(),
        ];


        return $this->createApiResponse($userData, 'Login successful.', Response::HTTP_OK);
    }

    /**
     * @throws RandomException
     * @throws TransportExceptionInterface
     */
    #[Route('/forgot-password', name: 'app_forgot_password', methods: ['POST'])]
    public function forgotPassword(Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? null;

        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->createApiResponse('Invalid or missing email.', Response::HTTP_BAD_REQUEST);
        }

        $user = $userRepository->findOneBy(['mail' => $email]);
        if (!$user) {
            return $this->createApiResponse('If an account with that email exists, we have sent an email to reset your password.', Response::HTTP_OK);
        }

        $resetToken = bin2hex(random_bytes(32));
        $user->setToken($resetToken);
        $entityManager->flush();

        $resetLink = "https://127.0.0.1:8000/reset-password?token=$resetToken";
        $email = (new Email())
            ->from('hackathon@esgi.com')
            ->to($user->getMail())
            ->subject('Confirmation de compte enseignant')
            ->html("<p>Please click on the following link to reset your password: <a href='$resetLink'>Reset Password</a></p>");


        $this->mailer->send($email);


        return $this->createApiResponse('If an account with that email exists, we have sent an email to reset your password.', Response::HTTP_OK);
    }

    #[Route('/reset-password', name: 'app_reset_password', methods: ['POST'])]
    public function resetPassword(Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $data = json_decode($request->getContent(), true);
        $token = $data['token'] ?? null;
        $newPassword = $data['newPassword'] ?? null;

        if (!$token || !$newPassword) {
            return $this->createApiResponse('Invalid or missing token or password.', Response::HTTP_BAD_REQUEST);
        }

        $user = $userRepository->findOneBy(['token' => $token]);
        if (!$user) {
            return $this->createApiResponse('Invalid token.', Response::HTTP_UNAUTHORIZED);
        }

        $user->setPassword($passwordHasher->hashPassword($user, $newPassword));
        $user->setToken(null);
        $entityManager->flush();

        return $this->createApiResponse('Password reset successfully.', Response::HTTP_OK);
    }


}
