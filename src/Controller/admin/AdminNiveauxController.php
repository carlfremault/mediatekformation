<?php

namespace App\Controller\admin;

use App\Entity\Niveau;
use App\Repository\FormationRepository;
use App\Repository\NiveauRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of AdminNiveauxController
 *
 * @author carlf
 */
class AdminNiveauxController extends AbstractController {

    /**
     * @var string
     */
    private const PAGEADMINNIVEAUX = "admin/admin.niveaux.html.twig";

    /**
     * @var FormationRepository
     */
    private $formationRepository;

    /**
     * @var NiveauRepository
     */
    private $niveauRepository;

    /**
     * @var EntityManagerInterface
     */
    private $om;

    /**
     * 
     * @param NiveauRepository $niveauRepository
     * @param EntityManagerInterface $om
     */
    function __construct(NiveauRepository $niveauRepository, FormationRepository $formationRepository, EntityManagerInterface $om) {
        $this->niveauRepository = $niveauRepository;
        $this->formationRepository = $formationRepository;
        $this->om = $om;
    }

    /**
     * @Route("/admin/niveaux", name="admin.niveaux")
     * @return Response
     */
    public function index(): Response {
        $niveaux = $this->niveauRepository->findAll();
        return $this->render(self::PAGEADMINNIVEAUX, [
                    'niveaux' => $niveaux
        ]);
    }

    /**
     * @Route ("/admin/niveau/suppr/{id}", name="admin.niveau.suppr")
     * @param Niveau $niveau
     * @return Response
     */
    public function suppr(Niveau $niveau, Request $request): Response {
        $formations = $this->formationRepository->findByEqualValue('niveau', $niveau->getId());
        if (count($formations) == 0) {
            $this->om->remove($niveau);
            $this->om->flush();            
            
        } else {           
            $this->addFlash(
            'notice',
            'Impossible de supprimer ce niveau. Il est utilisé pour (au moins) une des formations.'
        );
        }
        return $this->redirectToRoute('admin.niveaux');
    }
    
    /**
     * @Route("/admin/niveau/ajout/{champ}", name="admin.niveau.ajout")
     * @param type $champ
     * @param Request $request
     * @return Response
     */
    public function ajout($champ, Request $request) : Response {
        if ($this->isCsrfTokenValid('ajout_'.$champ, $request->get('_token'))) {
        $libelleNiveau = htmlentities($request->get("libelle"));
        $niveau = new Niveau();
        $niveau->setLibelle($libelleNiveau);
        $this->om->persist($niveau);
        $this->om->flush();
        }
        return $this->redirectToRoute('admin.niveaux');        
    }
}
