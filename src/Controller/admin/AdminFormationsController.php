<?php

namespace App\Controller\admin;

use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\FormationRepository;
use App\Repository\NiveauRepository;
use Doctrine\ORM\EntityManagerInterface;
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

    /**
     * @var string
     */
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
     * @var Niveaux[]
     */
    private $niveaux;
    
    

    /**
     * @var EntityManagerInterface
     */
    private $om;
            
    /**
     * @param FormationRepository $formationRepository
     * @param EntityManagerInterface $om
     */
    function __construct(FormationRepository $formationRepository, NiveauRepository $niveauRepository, EntityManagerInterface $om) {
        $this->formationRepository = $formationRepository;
        $this->niveauRepository = $niveauRepository;
        $this->om = $om;
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
    
    /**
     * @Route("/admin/tri/{champ}/{ordre}", name="admin.formations.sort")
     * @param type $champ
     * @param type $ordre
     * @return Response
     */
    public function sort($champ, $ordre): Response {
        $formations = $this->formationRepository->findAllOrderBy($champ, $ordre);
        return $this->render(self::PAGEADMINFORMATIONS, [
                    'formations' => $formations,
                    'niveaux' => $this->niveaux
        ]);
    }

    /**
     * @Route("/admin/filter/{champ}/{valeur}", name="admin.formations.filter")
     * @param type $champ
     * @param type $valeur
     * @return Response
     */
    public function filter($champ, $valeur = null): Response {
        $formations = $this->formationRepository->findByEqualValue($champ, $valeur);
        return $this->render(self::PAGEADMINFORMATIONS, [
                    'formations' => $formations,
                    'niveaux' => $this->niveaux
        ]);
    }

    /**
     * @Route("/admin/recherche/{champ}", name="admin.formations.findallcontain")
     * @param type $champ
     * @param Request $request
     * @return Response
     */
    public function findAllContain($champ, Request $request): Response {
        if ($this->isCsrfTokenValid('filtre_' . $champ, $request->get('_token'))) {
            $valeur = $request->get("recherche");
            $formations = $this->formationRepository->findByContainValue($champ, $valeur);
            return $this->render(self::PAGEADMINFORMATIONS, [
                        'formations' => $formations,
                        'niveaux' => $this->niveaux
            ]);
        }
        return $this->redirectToRoute("admin");
    }
    
    /**
     * @Route ("/admin/suppr/{id}", name="admin.formation.suppr")
     * @param Formation $formation
     * @return Response
     */
    public function suppr(Formation $formation): Response {
        $this->om->remove($formation);
        $this->om->flush();
        return $this->redirectToRoute('admin.formations');
    }
 
    /**
     * @route ("/admin/edit/{id}", name="admin.formation.edit")
     * @param Formation $formation
     * @param Request $request
     * @return Response
     */
    public function edit(Formation $formation, Request $request): Response {
       $formFormation = $this->createForm(FormationType::class, $formation);
       $formFormation->handleRequest($request);
       if($formFormation->isSubmitted() && $formFormation->isValid()){
           $this->om->flush();
           return $this->redirectToRoute('admin.formations');
       }
        return $this->render("admin/admin.formation.edit.html.twig", [
            'formation' => $formation,
            'niveaux'=>$this->niveaux,
            'formformation'=>$formFormation->createView()
        ]);
    }
    
    /**
     * @route ("/admin/ajout", name="admin.formation.ajout")
     * @param Request $request
     * @return Response
     */
    public function ajout(Request $request): Response {
        $formation = new Formation();
        $formFormation = $this->createForm(FormationType::class, $formation);
        $formFormation->handleRequest($request);
        if ($formFormation->isSubmitted() && $formFormation->isValid()) {
            $this->om->persist($formation);
            $this->om->flush();
            return $this->redirectToRoute('admin.formations');
        }
        return $this->render("admin/admin.formation.ajout.html.twig", [
                    'formation' => $formation,
                    'niveaux' => $this->niveaux,
                    'formformation' => $formFormation->createView()
        ]);
    }

}
