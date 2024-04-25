<?php

namespace App\DataFixtures;

use App\Entity\AboutPage;
use App\Entity\Atelier;
use App\Entity\AtelierContent;
use App\Entity\Ecole;
use App\Entity\Product;
use App\Entity\Reservation;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    private array $descriptions = [
        "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
        "Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.",
        "Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.",
        "Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.",
        "Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."
    ];

    public function load(ObjectManager $manager): void
    {
        $users = [];
        $ecoles = [];
        $ateliers = [];
        $products = [];
        $atelierContents = [];

        for ($i = 0; $i < 5; ++$i) {
            $user = new User();
            $user->setNom('Nom'.$i)
                ->setPrenom('Prénom'.$i)
                ->setMail('user'.$i.'@example.com')
                ->setPassword($this->passwordHasher->hashPassword($user, '123456789'))
                ->setRole($i === 4 ? 'ROLE_ADMIN' : 'ROLE_USER');
            $manager->persist($user);
            $users[] = $user;
        }


        for ($i = 0; $i < 5; ++$i) {
            $ecole = new Ecole();
            $ecole->setNom('Ecole '.$i);
            $manager->persist($ecole);
            $ecoles[] = $ecole;
        }

        for ($i = 0; $i < 10; ++$i) {
            $product = new Product();
            $product->setRegion('Region '.$i)
                ->setMillesime(rand(1900, 2020))
                ->setCepage('Cepage '.$i)
                ->setNom('Vin '.$i)
                ->setStock(rand(50, 200))
                ->setType('Type '.$i)
                ->setVolume(rand(750, 1500))
                ->setDescription($this->getRandomDescription())
            ;
            $manager->persist($product);
            $products[] = $product;
        }

        for ($i = 0; $i < 5; ++$i) {
            $atelierContent = new AtelierContent();
            $atelierContent->setThematique('Thématique '.$i)
                ->setNom('Contenu '.$i)
                ->setPrix(rand(100, 500));
            // Randomly assign products to this atelierContent
            for ($j = 0; $j < rand(1, 3); $j++) {
                $atelierContent->addProduct($products[array_rand($products)]);
            }
            $manager->persist($atelierContent);
            $atelierContents[] = $atelierContent;
        }

        foreach ($ecoles as $ecole) {
            for ($i = 0; $i < 3; ++$i) {
                $atelier = new Atelier();
                $atelier->setDateDebut(new \DateTime())
                    ->setDateFin(new \DateTime('+1 hour'))
                    ->setLimiteParticipant(rand(5, 20))
                    ->setEcole($ecole);

                $atelierContent = $atelierContents[array_rand($atelierContents)];
                $atelier->setAtelierContent($atelierContent);

                $manager->persist($atelier);
                $ateliers[] = $atelier;
            }
        }

        foreach ($users as $user) {
            for ($i = 0; $i < rand(1, 3); $i++) {
                $reservation = new Reservation();
                $reservation->addUser($user)
                    ->addAtelier($ateliers[array_rand($ateliers)])
                    ->setNombre(rand(1, 5))
                    ->setIsPaid(rand(0, 1) > 0.5);
                $manager->persist($reservation);
            }
        }

        $aboutPage = new AboutPage();
        $aboutPage->setTitle('Olivier Bonneton, oenologue')
            ->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut mattis  pulvinar felis, sit amet malesuada odio. Vivamus aliquam ipsum at nisl  pretium, et eleifend massa mollis. Fusce ac consequat mauris. Nulla nisl  erat, tempus vitae tincidunt in, tincidunt ac dolor. Phasellus dui  nisi, vulputate non turpis at, eleifend tempus ex. In egestas neque quis  dui eleifend luctus. Etiam vel lacus ex. Pellentesque sagittis, ligula  vehicula iaculis condimentum, eros tortor posuere ex, in ultrices dui  purus eu leo. Donec a diam quam. Suspendisse sodales massa eu placerat  efficitur. Aliquam volutpat est leo, at aliquam ante luctus eget.')
            ->setTitleSpan('passionné');
        $manager->persist($aboutPage);

        $manager->flush();
    }

    private function getRandomDescription(): string
    {
        return $this->descriptions[array_rand($this->descriptions)];
    }
}
