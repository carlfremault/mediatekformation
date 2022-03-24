<?php
namespace App\Controller;

use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur pour la page accueil. Affiche un message d'introduction au site ainsi que les deux formations les plus récentes.
 *
 * @author emds
 */
class AccueilController extends AbstractController{
    
    /**
     *
     * @var FormationRepository
     */
    private $repository;
    
    /**
     * Constructeur. Valorise $repository avec le repository pour les formations
     * @param FormationRepository $repository
     */
    public function __construct(FormationRepository $repository) {
        $this->repository = $repository;
    }    
    
    /**
     * Route principale (et unique) pour la page d'accueil. Récupère et affiche les deux formations les plus récentes.
     * @Route("/", name="accueil")
     * @return Response
     */
    public function index(): Response{
        $formations = $this->repository->findAllLasted(2);
        return $this->render("pages/accueil.html.twig", [
            'formations' => $formations
        ]);  
    }
}
