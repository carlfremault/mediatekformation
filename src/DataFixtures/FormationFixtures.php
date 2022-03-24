<?php

namespace App\DataFixtures;

use App\Repository\FormationRepository;
use App\Repository\NiveauRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class FormationFixtures extends Fixture
{
/**
     *
     * @var FormationRepository
     */
    private $formationRepository;
    
    /**
     *
     * @var NiveauRepository
     */
    private $niveauRepository;
    
    /**
     * @param FormationRepository $formationRepository
     * @param NiveauRepository $niveauRepository
     */
    function __construct(FormationRepository $formationRepository, NiveauRepository $niveauRepository) {
        $this->formationRepository = $formationRepository;
        $this->niveauRepository = $niveauRepository;
    }

    /**
     * Fonction 'load' pour remplir les niveaux des différentes formations avec un niveau aléatoire
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $formations = $this->formationRepository->findAll();
        $niveaus = $this->niveauRepository->findAll();
        foreach($formations as $formation) {
            $id = rand(0, 2);
            $unNiveau = $niveaus[$id];
            $formation->setNiveau($unNiveau);
            $manager->persist($formation);
        }
        $manager->flush();
    }
}
