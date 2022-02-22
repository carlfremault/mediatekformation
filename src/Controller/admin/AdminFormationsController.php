<?php

namespace App\Controller\admin;

use App\Repository\FormationRepository;
use App\Repository\NiveauRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of AdminFormationsController
 *
 * @author carlf
 */
class AdminFormationsController extends AbstractController {

    private const PAGEADMINFORMATIONS = "admin/admin.formations.html.twig";
        
    /**
     * @var FormationRepository
     */
    private $formationRepository;

    /**
     * @var NiveauRepository
     */
    private $niveauRepository;

    /**
     *
     * @var Niveaux[]
     */
    private $niveaux;

    /**
     * 
     * @param FormationRepository $formationRepository
     */
    function __construct(FormationRepository $formationRepository, NiveauRepository $niveauRepository) {
        $this->formationRepository = $formationRepository;
        $this->niveauRepository = $niveauRepository;
        $this->niveaux = $this->niveauRepository->findAll();
    }
    
        /**
     * @Route("/admin", name="admin.formations")
     * @return Response
     */
    public function index(): Response {
        $formations = $this->formationRepository->findAllOrderBy('publishedAt', 'DESC');
        return $this->render(self::PAGEADMINFORMATIONS, [
                    'formations' => $formations,
                    'niveaux' => $this->niveaux
        ]);
    }
}
