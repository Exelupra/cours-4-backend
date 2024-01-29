<?php

namespace App\Command;

use App\Entity\Personne;
use App\Entity\Batiment;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Random\RandomException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'ajouter-donnees',
    description: 'Ajouter des données de test à la base de données',
)]
class AjouterDonneesCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this->setDescription('Ajouter des données de test à la base de données');
    }

    /**
     * @throws RandomException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $faker = Factory::create();

        // Ajouter des données pour les bâtiments
        for ($i = 0; $i < 5; $i++) {
            $batiment = new Batiment();
            $batiment->setNom($faker->company);
            $batiment->setAdresse($faker->address);
            $this->entityManager->persist($batiment);
        }

        // Ajouter des données pour les personnes liées à des bâtiments
        for ($i = 0; $i < 10; $i++) {
            $personne = new Personne();
            $personne->setNom($faker->lastName);
            $personne->setPrenom($faker->firstName);
            $personne->setJob($faker->jobTitle);

            // Lier la personne à un bâtiment au hasard
            $batiment = $this->entityManager->getRepository(Batiment::class)->find(random_int(1, 5));
            $personne->setBatiment($batiment);

            $this->entityManager->persist($personne);
        }

        $this->entityManager->flush();

        $io->success('Données de test ajoutées avec succès à la base de données.');

        return Command::SUCCESS;
    }
}
