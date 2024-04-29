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
        "Explorez la diversité des vins français à travers un voyage sensoriel captivant, idéal pour les débutants et les amateurs passionnés.",
        "Plongez dans l'univers envoûtant des vins rouges, découvrez leurs arômes riches et leurs nuances complexes lors de cette expérience sensorielle intense.",
        "Parcourez les délices culinaires et vinicoles de la région du Rhône, où chaque dégustation vous transporte dans un monde de plaisirs gustatifs et de découvertes épicuriennes.",
        "Partez à la rencontre des terroirs emblématiques de la Bourgogne, où la finesse des vins et la richesse des traditions vous enchanteront à chaque dégustation.",
        "Explorez la palette infinie de saveurs offerte par les vins bordelais, où l'élégance et la complexité se marient pour créer des expériences gustatives inoubliables."
    ];

    private array $thematiques = [
        "Exploration terrestre",
        "Exploration celeste"
    ];

    private array $nomAtelier = [
        "Ma Première Dégustation : Tour de France",
        "Rouges Passion : Éclats et Arômes des Vins Rouges",
        "Les Trésors du Rhône : Un Parcours Épicurien",
        "Splendeurs de la Bourgogne : Éloge des Terroirs",
        "Bordeaux, Terre de Vignes : Une Palette de Saveurs"
    ];

    private array $prenoms = [
        "Jean",
        "Marie",
        "Claire",
        "Pierre",
        "Sophie"
    ];

    private array $noms = [
        "Dupont",
        "Dubois",
        "Lefebvre",
        "Martin",
        "Bernard"
    ];

    private array $nomsEcoleSup = [
        "École Supérieure d'Ingénieurs",
        "École Supérieure de Commerce",
        "École Supérieure des Beaux-Arts",
        "École Supérieure de Design",
        "École Supérieure de Journalisme"
    ];

    private array $nomsRegions = [
        "Île-de-France",
        "Provence-Alpes-Côte d'Azur",
        "Nouvelle-Aquitaine",
        "Auvergne-Rhône-Alpes",
        "Occitanie"
    ];

    private array $cepagesVins = [
        "Chardonnay",
        "Cabernet Sauvignon",
        "Merlot",
        "Pinot Noir",
        "Sauvignon Blanc"
    ];

    private array $nomsVins = [
        "Château Margaux",
        "Domaine de la Romanée-Conti",
        "Opus One",
        "Screaming Eagle",
        "Penfolds Grange",
        "Château Lafite Rothschild",
        "Château Mouton Rothschild",
        "Château Latour",
        "Château Haut-Brion",
        "Château d'Yquem"
    ];

    private array $typesVins = [
        "Vin rouge",
        "Vin blanc",
        "Vin rosé",
        "Vin effervescent",
        "Vin doux"
    ];

    private array $notesOenologue = [
        "Un vin élégant avec des arômes de fruits rouges et des tanins souples.",
        "Ce vin blanc offre une belle fraîcheur avec des notes d'agrumes et une finale minérale.",
        "Un rosé léger et fruité, parfait pour les journées ensoleillées.",
        "Vin effervescent avec des bulles fines et une belle vivacité en bouche.",
        "Ce vin doux présente des arômes de fruits confits et une douceur enveloppante."
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
            $user->setNom($this->getRandom($this->noms))
                ->setPrenom($this->getRandom($this->prenoms))
                ->setMail('user'.$i.'@example.com')
                ->setPassword($this->passwordHasher->hashPassword($user, '123456789'))
                ->setRole($i === 4 ? 'ROLE_ADMIN' : 'ROLE_USER');
            $manager->persist($user);
            $users[] = $user;
        }


        for ($i = 0; $i < 5; ++$i) {
            $ecole = new Ecole();
            $ecole->setNom($this->getRandom($this->nomsEcoleSup));
            $manager->persist($ecole);
            $ecoles[] = $ecole;
        }

        for ($i = 0; $i < 10; ++$i) {
            $product = new Product();
            $product->setRegion($this->getRandom($this->nomsRegions))
                ->setMillesime(rand(1900, 2020))
                ->setCepage($this->getRandom($this->cepagesVins))
                ->setNom($this->getRandom($this->nomsVins))
                ->setStock(rand(50, 200))
                ->setType($this->getRandom($this->typesVins))
                ->setVolume(rand(750, 1500))
                ->setDescription($this->getRandom($this->notesOenologue))
            ;
            $manager->persist($product);
            $products[] = $product;
        }

        for ($i = 0; $i < 5; ++$i) {
            $atelierContent = new AtelierContent();
            $atelierContent->setThematique($this->getRandom($this->thematiques))
                ->setNom($this->getRandom($this->nomAtelier))
                ->setPrix(rand(100, 500))
                ->setDescription($this->getRandom($this->descriptions));
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
            ->setDescription('Passionné par l\'art de la dégustation, je suis un œnologue dévoué à l\'exploration des saveurs et des terroirs. Fort de mon expérience et de ma formation, je m\'engage à guider les amateurs de vin dans un voyage sensoriel unique, où chaque dégustation révèle les secrets et les subtilités des plus grands crus.')
            ->setTitleSpan('passionné');
        $manager->persist($aboutPage);

        $manager->flush();
    }

    private function getRandom($item): string
    {
        return $item[array_rand($item)];
    }
}
