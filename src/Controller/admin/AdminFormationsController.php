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
 * Contrôleur pour la page admin des formations
 *
 * @author carlf
 */
class AdminFormationsController extends AbstractController {

    /**
     * Vue des formations
     * @var string
     */
    private const PAGEADMINFORMATIONS = "admin/admin.formations.html.twig";
    
    /**
     * Route pour la page backoffice des formations
     * @var string
     */
    private const ROUTEADMINFORMATIONS = "admin.formations";    
        
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
     * Constructeur. Valorise $formationRepository, $niveauRepository, EntityManagerInterface $om. Remplit le tableau $niveaux avec les niveaux de la base de données.
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
     * Route principale pour la partie backoffice. Affiche tous les formations disponibles. Permet d'ajouter, modifier ou supprimer une formation. Permet de trier sur le titre et la date de parution, et de filtrer sur le titre ou le niveau.
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
     * Route qui permet de trier la liste des formations par rapport à un des champs par ordre ascendant ou descendant. 
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
     * Route qui permet de filtrer les formations par rapport à un des champs et pour une valeur saisie. Utilisé par le menu déroulant qui filtre les niveaux.
     * @Route("/admin/filter/{champ}/{valeur}", name="admin.formations.filter")
     * @param type $champ
     * @param type $valeur
     * @return Response
     */
    public function filter($champ, $valeur = null): Response {
        $valeur = htmlentities($valeur);
        $formations = $this->formationRepository->findByEqualValue($champ, $valeur);
        return $this->render(self::PAGEADMINFORMATIONS, [
                    'formations' => $formations,
                    'niveaux' => $this->niveaux
        ]);
    }

    /**
     * Route qui permet de filtrer les formations par rapport à un champ contenant une valeur saisie. Utilisé pour filtrer sur les titres des formations.
     * @Route("/admin/recherche/{champ}", name="admin.formations.findallcontain")
     * @param type $champ
     * @param Request $request
     * @return Response
     */
    public function findAllContain($champ, Request $request): Response {
        if ($this->isCsrfTokenValid('filtre_' . $champ, $request->get('_token'))) {
            $valeur = htmlentities($request->get("recherche"));
            $formations = $this->formationRepository->findByContainValue($champ, $valeur);
            return $this->render(self::PAGEADMINFORMATIONS, [
                        'formations' => $formations,
                        'niveaux' => $this->niveaux
            ]);
        }
        return $this->redirectToRoute("admin");
    }
    
    /**
     * Route qui permet de supprimer une formation sélectionnée.
     * @Route ("/admin/suppr/{id}", name="admin.formation.suppr")
     * @param Formation $formation
     * @return Response
     */
    public function suppr(Formation $formation): Response {
        $this->om->remove($formation);
        $this->om->flush();
        return $this->redirectToRoute(self::ROUTEADMINFORMATIONS);
    }
 
    /**
     * Route qui permet de modifier une formation sélectionnée. Ouvre un formulaire avec les champs préremplis.
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
           return $this->redirectToRoute(self::ROUTEADMINFORMATIONS);
       }
        return $this->render("admin/admin.formation.edit.html.twig", [
            'formation' => $formation,
            'niveaux'=>$this->niveaux,
            'formformation'=>$formFormation->createView()
        ]);
    }
    
    /**
     * Route qui permet d'ajouter une formation. Ouvre un formulaire.
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
            return $this->redirectToRoute(self::ROUTEADMINFORMATIONS);
        }
        return $this->render("admin/admin.formation.ajout.html.twig", [
                    'formation' => $formation,
                    'niveaux' => $this->niveaux,
                    'formformation' => $formFormation->createView()
        ]);
    }

}
